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
        try{
            $where = array(
                'unitePayID' => $unitePayID
            );
            $payModel = $this->memberPayDetailModel;
            $payDetail = $payModel->select($where)->current();
            if($payDetail['payStatus'] == BaseConst::PAY_DETAIL_STATUS_PAID) throw new \Exception(ApiError::ORDER_HAVE_PAID_MSG, ApiError::ORDER_HAVE_PAID);
            $payModel->beginTransaction();
            $payModel->update(array('payStatus' => BaseConst::PAY_DETAIL_STATUS_PAID), $where);
            $this->memberOrderModel->update(array('orderStatus' => BaseConst::ORDER_STATUS_PAID), $where);

            $payModel->commit();
            return true;
        }catch(\Exception $e){
            $payModel->rollback();

            throw new \Exception(ApiError::ORDER_TRANSACTION_FAILED_MSG, ApiError::ORDER_TRANSACTION_FAILED);
        }
    }

}
