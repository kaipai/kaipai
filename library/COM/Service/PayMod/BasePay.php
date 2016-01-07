<?php

namespace COM\Service\PayMod;

use Api\Exception\OrderException;
use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\Sms;
use Base\ConstDir\BaseConst;
use Zend\Db\Adapter\Exception\InvalidQueryException;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Operator;
use Zend\ServiceManager\ServiceManager;

abstract class BasePay
{
    protected $sm;
    public function __construct(ServiceManager $sm){
        $this->sm = $sm;
    }

    /**
     * 支付回调处理
     * @param $unitePayID 支付号
     * @param $sourceGift 来源是否是赠送商品
     */
    public function notify($unitePayID, $sourceGift = false){

        $payModel = $this->sm->get('Api\Model\MemberPayDetail');
        $payDetail = $payModel->select(array('UnitePayID' => $unitePayID))->current();
        if(empty($payDetail)) throw new OrderException(ApiError::PAY_DETAIL_NOT_EXIST_MSG, ApiError::PAY_DETAIL_NOT_EXIST);
        if($payDetail['GenerateType'] == BaseConst::PAY_GENERATE_TYPE_RECHARGE){
            return $this->rechargeNotify($payDetail);
        }
        $orderModel = $this->sm->get('Api\Model\MemberOrder');
        $caseModel =  $this->sm->get('Api\Model\MemberCustomCase');
        $payOrders = $orderModel->select(array('UnitePayID' => $unitePayID))->toArray();
        $memberInfo = $this->sm->get('Api\Model\MemberInfo')->select(array('MemberID' => $payDetail['MemberID']))->current();
        try{
            $cardModel = $this->sm->get('Api\Model\MemberServiceCard');
            $productModel = $this->sm->get('Api\Model\Product');
            $payModel->beginTransaction();
            $payModel->update(array('PayStatus' => BaseConst::PAY_STATUS_PAID, 'PaidMoney' => $payDetail['WaitPayMoney'], 'Paytime' => time()), array('UnitePayID' => $unitePayID));

            if(!empty($payDetail['RechargeMoneyDeduction'])){
                if($payDetail['RechargeMoneyDeduction'] > $memberInfo['RechargeMoney']){
                    $this->sm->get('Api\Model\MemberInfo')->update(array('RechargeMoney' => new Expression('RechargeMoney + ' . $payDetail['WaitPayMoney'])), array('MemberID' => $payDetail['MemberID']));
                    $recharLogData = array(
                        'Money' => $payDetail['WaitPayMoney'],
                        'UnitePayID' => $payDetail['UnitePayID'],
                        'MemberID' => $payDetail['MemberID'],
                        'Source' => BaseConst::RECHARGE_MONEY_NOT_ENOUGH,
                    );
                    $this->sm->get('Api\Model\MemberRechargeMoneyLog')->insert($recharLogData);
                    throw new \Exception(ApiError::PAY_RECHARGE_MONEY_NOT_ENOUGH_MSG, ApiError::PAY_RECHARGE_MONEY_NOT_ENOUGH);
                }
                $this->sm->get('Api\Model\MemberInfo')->update(array('RechargeMoney' => new Expression('RechargeMoney - ' . $payDetail['RechargeMoneyDeduction'])), array('MemberID' => $payDetail['MemberID']));
            }
            if(!empty($payDetail['UseBonusID'])){
                $bonusInfo = $this->sm->get('Api\Model\MemberBonus')->select(array(
                    'MemberID' => $memberInfo['MemberID'], 'BonusID' => $payDetail['UseBonusID'],
                    'BonusStatus' => BaseConst::BONUS_STATUS_ENABLE, 'ExpireTime' . Operator::OP_GT . '?' => time(),
                ))->current();
                if(empty($bonusInfo)){
                    throw new \Exception(ApiError::PAY_BONUS_NOT_ENABLE_MSG, ApiError::PAY_BONUS_NOT_ENABLE);
                }
                $this->sm->get('Api\Model\MemberBonus')->update(array('BonusStatus' => BaseConst::BONUS_STATUS_DISABLE, 'Usetime' => time()), array('BonusID' => $payDetail['UseBonusID']));
            }


            foreach($payOrders as $v){
                $update = array('OrderStatus' => BaseConst::ORDER_STATUS_PAID);

                if(!empty($v['ProductID'])){
                    $productModel->update(array('SoldCount' => new Expression('SoldCount + 1')), array('ProductID' => $v['ProductID']));

                    $v['ProductSnapshot'] = json_decode($v['ProductSnapshot'], true);
                    if($v['OrderType'] == BaseConst::ORDER_TYPE_DEPOSIT && !$sourceGift){
                        $update = array('OrderStatus' => BaseConst::ORDER_STATUS_PAID_DEPOSIT);
                    }
                    if($v['ProductSnapshot']['NeedTest']){
                        $caseData = array(
                            'MemberID' => $v['MemberID'],
                            'ClientMobile' => $memberInfo['Mobile'],
                            'ClientName' => $memberInfo['MemberName'],
                            'CaseCode' => $caseModel->genCaseCode(),
                            'Title' => BaseConst::CAR_TEST_SERVICE_NAME,
                            'Content' => BaseConst::CAR_TEST_SERVICE_NAME,
                            'CarModel' => $v['CarModel'],
                            'CarRepoID' => $v['CarRepoID'],
                            'OrderID' => $v['OrderID'],
                        );
                        $caseModel->insert($caseData);
                        $logData = array(
                            'CaseID' => $caseModel->getLastInsertValue(),
                            'Content' => BaseConst::$caseStatus[BaseConst::CASE_STATUS_NOT_ACCEPTED],
                        );
                        $this->sm->get('Api\Model\CaseStatusChangeLog')->insert($logData);
                    }else{
                        if(empty($v['ProductSnapshot']['SilenceTime'])){
                            $cardModel->generateCards($unitePayID);
                        }
                    }
                }
                if(!empty($v['SchemeID'])){
                    $orderModel->update(array('IsHide' => 0), array('OrderID' => $v['OrderID']));
                }
                if(!empty($v['ParentOrderID'])){
                    $orderModel->update(
                        array('OrderStatus' => BaseConst::ORDER_STATUS_PAID),
                        array('OrderStatus' => BaseConst::ORDER_STATUS_PAID_DEPOSIT, 'OrderID' => $v['ParentOrderID'])
                    );
                }

                $orderModel->update($update, array('OrderID' => $v['OrderID']));
            }


            $payModel->commit();
            foreach($payOrders as $v){
                if(!empty($v['ProductID'])){
                    $v['ProductSnapshot'] = json_decode($v['ProductSnapshot'], true);
                    $smsContent = str_replace(array('{$memberName}', '{$productName}'), array($memberInfo['MemberName'], $v['ProductSnapshot']['Name']), Sms::PRODUCT_PAID_MSG);
                    $this->sm->get('COM\Service\SmsService')->sendSMS($memberInfo['Mobile'], $smsContent);
                }
            }
            return true;
        }catch(InvalidQueryException $e){
            $payModel->rollback();

            throw new OrderException(ApiError::ORDER_TRANSACTION_FAILED_MSG, ApiError::ORDER_TRANSACTION_FAILED);
        }catch(\Exception $e){
            $payModel->rollback();

            throw new OrderException(ApiError::ORDER_TRANSACTION_FAILED_MSG, ApiError::ORDER_TRANSACTION_FAILED);
        }
    }

    private function rechargeNotify($payDetail){
        $payModel = $this->sm->get('Api\Model\MemberPayDetail');
        $orderModel = $this->sm->get('Api\Model\MemberOrder');
        $payOrders = $orderModel->select(array('UnitePayID' => $payDetail['UnitePayID']))->toArray();
        try {
            $payModel->beginTransaction();
            $payModel->update(array('PayStatus' => BaseConst::PAY_STATUS_PAID, 'PaidMoney' => $payDetail['WaitPayMoney']), array('UnitePayID' => $payDetail['UnitePayID']));

            foreach($payOrders as $v){
                $update = array('OrderStatus' => BaseConst::ORDER_STATUS_PAID);
                $orderModel->update($update, array('OrderID' => $v['OrderID']));
            }
            $this->sm->get('Api\Model\MemberInfo')->update(array('RechargeMoney' => new Expression('RechargeMoney + ' . $payDetail['WaitPayMoney'])), array('MemberID' => $payDetail['MemberID']));
            $recharLogData = array(
                'Money' => $payDetail['WaitPayMoney'],
                'UnitePayID' => $payDetail['UnitePayID'],
                'MemberID' => $payDetail['MemberID'],
                'Source' => BaseConst::DEFAULT_RECHARGE_SOURCE,
            );
            $this->sm->get('Api\Model\MemberRechargeMoneyLog')->insert($recharLogData);

            $payModel->commit();
            return true;
        }catch(\Exception $e){
            $payModel->rollback();
            throw new OrderException(ApiError::ORDER_TRANSACTION_FAILED_MSG, ApiError::ORDER_TRANSACTION_FAILED);
        }

    }
}
