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
    public function notify($unitePayID){
        try{
            $where = array(
                'unitePayID' => $unitePayID
            );
            $orderModel = $this->memberOrderModel;
            $orderInfo = $orderModel->getOrderInfo($unitePayID);
            if($orderInfo['orderStatus'] != 1) throw new \Exception(ApiError::ORDER_HAVE_PAID_MSG, ApiError::ORDER_HAVE_PAID);
            $orderModel->beginTransaction();
            $orderModel->update(array('paidMoney' => $orderInfo['payMoney']), $where);
            if(!empty($orderInfo['customizationID'])){
                $orderModel->update(array('orderStatus' => 2), $where);
                $this->customizationModel->update(array('lastNum' => new Expression('lastNumber+1'), 'boughtCount' => new Expression('boughtCount+1')));
            }else{
                $orderModel->update(array('orderStatus' => 3), $where);
            }

            $orderModel->commit();
            return true;
        }catch(\Exception $e){
            $orderModel->rollback();

            throw new \Exception(ApiError::ORDER_TRANSACTION_FAILED_MSG, ApiError::ORDER_TRANSACTION_FAILED);
        }
    }

    public function productNotify($unitePayID){
        try{

            $payDetail = $this->productModel->select(array('unitePayID' => $unitePayID))->current();
            if($payDetail['isPaid']) throw new \Exception(ApiError::ORDER_HAVE_PAID_MSG, ApiError::ORDER_HAVE_PAID);
            $this->productModel->beginTransaction();
            $this->productModel->update(array('auctionStatus' => 1, 'isPaid' => 1), array('unitePayID' => $unitePayID));

            $this->productModel->commit();
            return true;
        }catch(\Exception $e){
            $this->productModel->rollback();

            throw new \Exception(ApiError::ORDER_TRANSACTION_FAILED_MSG, ApiError::ORDER_TRANSACTION_FAILED);
        }
    }

    public function specialNotify($unitePayID){
        try{

            $special = $this->specialModel->select(array('unitePayID' => $unitePayID))->current();
            if($special['isPaid']) throw new \Exception(ApiError::ORDER_HAVE_PAID_MSG, ApiError::ORDER_HAVE_PAID);
            $this->specialModel->beginTransaction();
            $this->specialModel->update(array('auctionStatus' => 1, 'isPaid' => 1), array('unitePayID' => $unitePayID));

            $this->specialModel->commit();
            return true;
        }catch(\Exception $e){
            $this->specialModel->rollback();

            throw new \Exception(ApiError::ORDER_TRANSACTION_FAILED_MSG, ApiError::ORDER_TRANSACTION_FAILED);
        }
    }

    public function finalNotify($unitePayID){
        try{
            $payDetail = $this->memberOrderModel->getFinalOrderInfo($unitePayID);
            if($payDetail['payStatus'] != 1) throw new \Exception(ApiError::ORDER_HAVE_PAID_MSG, ApiError::ORDER_HAVE_PAID);
            $this->memberOrderModel->beginTransaction();
            $this->memberOrderModel->update(array('orderStatus' => 3), array('finalUnitePayID' => $unitePayID));
            $this->memberPayDetailModel->update(array('paidMoney' => $payDetail['payMoney']), array('unitePayID' => $unitePayID));

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
