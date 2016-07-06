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
     */
    public function notify($unitePayID, $useRechargeMoney = 0){
        $orderModel = $this->memberOrderModel;
        $where = array(
            'unitePayID' => $unitePayID
        );
        $orderInfo = $orderModel->getOrderInfo($unitePayID);
        if($orderInfo['orderStatus'] != 1) throw new \Exception(ApiError::ORDER_HAVE_PAID_MSG, ApiError::ORDER_HAVE_PAID);
        try{
            $orderModel->beginTransaction();
            $this->memberPayDetailModel->update(array('paidMoney' => $orderInfo['payMoney'], 'payTime' => time()), $where);
            if(!empty($orderInfo['customizationID'])){
                $orderModel->update(array('orderStatus' => 2), $where);
                $this->customizationModel->update(array('lastNum' => new Expression('lastNum+1'), 'boughtCount' => new Expression('boughtCount+1')), array('customizationID' => $orderInfo['customizationID']));
            }else{
                $orderModel->update(array('orderStatus' => 3), $where);


                $storeInfo = $this->storeModel->setColumns(array('memberID'))->select(array('storeID' => $orderInfo['storeID']))->current();
                $productSnapshot = json_decode($orderInfo['productSnapshot'], true);
                $productName = $productSnapshot['productName'];
                $this->notificationModel->insert(array('type' => 4, 'memberID' => $storeInfo['memberID'], 'content' => '您的拍品<<' . $productName . '>>已被付款, 请在72小时内发货，否则买家可投诉客服处理。'));

            }
            if(!empty($useRechargeMoney)){
                $this->memberInfoModel->update(array('rechargeMoney' => new Expression('rechargeMoney - ' . $useRechargeMoney)), array('memberID' => $orderInfo['memberID']));
                if(!empty($orderInfo['customizationID'])){
                    $source = '余额付款-定制商品定金';
                }else{
                    $source = '余额付款-竞拍拍品';
                }
                $this->memberRechargeMoneyLogModel->insert(array('memberID' => $orderInfo['memberID'], 'money' => $useRechargeMoney, 'unitePayID' => $unitePayID, 'source' => $source, 'type' => 2));
            }


            $orderModel->commit();
            return true;
        }catch(\Exception $e){

            $orderModel->rollback();

            throw new \Exception(ApiError::ORDER_TRANSACTION_FAILED_MSG, ApiError::ORDER_TRANSACTION_FAILED);
        }
    }

    public function productNotify($unitePayID, $useRechargeMoney = 0){
        try{

            $product = $this->productModel->select(array('unitePayID' => $unitePayID))->current();
            if($product['isPaid']) throw new \Exception(ApiError::ORDER_HAVE_PAID_MSG, ApiError::ORDER_HAVE_PAID);
            $this->productModel->beginTransaction();
            if($product['publishAtOnce']){
                $this->productModel->update(array('auctionStatus' => 2, 'isPaid' => 1, 'startTime' => time(), 'endTime' => strtotime('+1 day')), array('unitePayID' => $unitePayID));
            }else{
                $this->productModel->update(array('isPaid' => 1, 'startTime' => new Expression('publishStartTime'), 'endTime' => new Expression('publishEndTime')), array('unitePayID' => $unitePayID));
            }

            $memberInfo = $this->memberInfoModel->select(array('storeID' => $product['storeID']))->current();
            if(!empty($useRechargeMoney)){
                $this->memberInfoModel->update(array('rechargeMoney' => new Expression('rechargeMoney - ' . $useRechargeMoney)), array('memberID' => $memberInfo['memberID']));
                $this->memberRechargeMoneyLogModel->insert(array('memberID' => $memberInfo['memberID'], 'money' => $useRechargeMoney, 'unitePayID' => $unitePayID, 'source' => '余额付款-添加拍品', 'type' => 2));
            }
            $this->productModel->commit();
            return true;
        }catch(\Exception $e){
            $this->productModel->rollback();

            throw new \Exception(ApiError::ORDER_TRANSACTION_FAILED_MSG, ApiError::ORDER_TRANSACTION_FAILED);
        }
    }

    public function specialNotify($unitePayID, $useRechargeMoney = 0){
        try{

            $special = $this->specialModel->select(array('unitePayID' => $unitePayID))->current();
            if($special['isPaid']) throw new \Exception(ApiError::ORDER_HAVE_PAID_MSG, ApiError::ORDER_HAVE_PAID);
            $this->specialModel->beginTransaction();
            $this->specialModel->update(array('isPaid' => 1, 'verifyStatus' => 1), array('unitePayID' => $unitePayID));
            $memberInfo = $this->memberInfoModel->select(array('storeID' => $special['storeID']))->current();
            if(!empty($useRechargeMoney)){
                $this->memberInfoModel->update(array('rechargeMoney' => new Expression('rechargeMoney - ' . $useRechargeMoney)), array('memberID' => $memberInfo['memberID']));
                $this->memberRechargeMoneyLogModel->insert(array('memberID' => $memberInfo['memberID'], 'money' => $useRechargeMoney, 'unitePayID' => $unitePayID, 'source' => '余额付款-添加专场', 'type' => 2));
            }
            $this->specialModel->commit();
            return true;
        }catch(\Exception $e){
            $this->specialModel->rollback();

            throw new \Exception(ApiError::ORDER_TRANSACTION_FAILED_MSG, ApiError::ORDER_TRANSACTION_FAILED);
        }
    }

    public function finalNotify($unitePayID, $useRechargeMoney = 0){
        try{
            $orderInfo = $this->memberOrderModel->getFinalOrderInfo($unitePayID);
            $this->memberOrderModel->beginTransaction();
            $this->memberOrderModel->update(array('orderStatus' => 6), array('finalUnitePayID' => $unitePayID));
            $this->memberPayDetailModel->update(array('paidMoney' => $orderInfo['payMoney']), array('unitePayID' => $unitePayID));
            if(!empty($useRechargeMoney)){
                $this->memberInfoModel->update(array('rechargeMoney' => new Expression('rechargeMoney - ' . $useRechargeMoney)), array('memberID' => $orderInfo['memberID']));
                $this->memberRechargeMoneyLogModel->insert(array('memberID' => $orderInfo['memberID'], 'money' => $useRechargeMoney, 'unitePayID' => $unitePayID, 'source' => '余额付款-定制商品尾款', 'type' => 2));
            }
            $this->memberOrderModel->commit();
            return true;
        }catch(\Exception $e){
            $this->memberOrderModel->rollback();

            throw new \Exception(ApiError::ORDER_TRANSACTION_FAILED_MSG, ApiError::ORDER_TRANSACTION_FAILED);
        }
    }

    public function __get($name){
        $isModel = stripos($name, 'Model');
        $isService = stripos($name, 'Service');
        if($isModel !== false){
            return $this->sm->get('Api\Model\\' . ucfirst(substr($name, 0, -5)));
        }elseif($isService !== false){
            return $this->sm->get('COM\Service\\' . ucfirst($name));
        }
    }

}
