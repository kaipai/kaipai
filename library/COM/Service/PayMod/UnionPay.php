<?php

namespace COM\Service\PayMod;


use Base\ConstDir\Api\ApiError;

class UnionPay extends BasePay
{

    public function doPay($unitePayID = null) {

        if(empty($unitePayID)) throw new \Exception(ApiError::PARAMETER_MISSING_MSG, ApiError::PARAMETER_MISSING);

        $payDetail = $this->sm->get('Api\Model\MemberPayDetail')->select(array('UnitePayID' => $unitePayID))->current();
        if (empty($payDetail)) throw new \Exception(ApiError::PAY_DETAIL_NOT_EXIST_MSG, ApiError::PAY_DETAIL_NOT_EXIST);

        include __DIR__ . '/lib/Union/unionpay.php';
        $config = $this->sm->get("Config");
        $order['OrderID'] = $unitePayID; //订单号16位
        $order['OrderPrice'] = $payDetail['WaitPayMoney'];  //金额

        $unionPay = new \Unionpay($config[getenv('APP_ENV')]['unionPayConfig']);
        $tn = $unionPay->getTN($order);
        if(empty($tn)) throw new \Exception(ApiError::UNION_PAY_TN_GET_FAILED_MSG, ApiError::UNION_PAY_TN_GET_FAILED);
        return $tn;

    }

    public function goPay($unitePayID = null) {
        if(empty($unitePayID)) throw new \Exception(ApiError::PARAMETER_MISSING_MSG, ApiError::PARAMETER_MISSING);

        $payDetail = $this->sm->get('Api\Model\MemberPayDetail')->select(array('UnitePayID' => $unitePayID))->current();
        if (empty($payDetail)) throw new \Exception(ApiError::PAY_DETAIL_NOT_EXIST_MSG, ApiError::PAY_DETAIL_NOT_EXIST);

        include __DIR__ . '/lib/Union/unionpay.php';
        $config = $this->sm->get("Config");
        $unionPay = new \Unionpay($config[getenv('APP_ENV')]['unionPayConfig']);
        $payForm = $unionPay->goPay($payDetail);

        return $payForm;
    }

}
