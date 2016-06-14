<?php

namespace COM\Service\PayMod;


use Base\ConstDir\Api\ApiError;

class UnionPay extends BasePay
{

    public function doPay($unitePayID = null, $price) {
        if(empty($unitePayID)) throw new \Exception(ApiError::COMMON_ERROR, ApiError::PARAMETER_MISSING);

        $payDetail = $this->memberPayDetailModel->select(array('unitePayID' => $unitePayID))->current();
        if (empty($payDetail)) throw new \Exception(ApiError::COMMON_ERROR, '支付信息不存在');

        include __DIR__ . '/lib/Union/unionpay.php';
        $config = $this->sm->get("Config");

        $unionPay = new \Unionpay($config['unionPayConfig']);
        $payDetail = array(
            'unitePayID' => $unitePayID,
            'payMoney' => $price,
        );
        $payForm = $unionPay->goPay($payDetail);

        return $payForm;
    }

    public function productDoPay($unitePayID = null, $price) {

        include __DIR__ . '/lib/Union/unionpay.php';
        $config = $this->sm->get("Config");
        $unionPay = new \Unionpay($config['unionPayConfig']);
        $payDetail = array(
            'unitePayID' => $unitePayID,
            'payMoney' => $price,
        );
        $payForm = $unionPay->goPay($payDetail);

        return $payForm;
    }

    public function specialDoPay($unitePayID = null, $price) {

        include __DIR__ . '/lib/Union/unionpay.php';
        $config = $this->sm->get("Config");
        $unionPay = new \Unionpay($config[getenv('APP_ENV')]['unionPayConfig']);
        $payDetail = array(
            'unitePayID' => $unitePayID,
            'payMoney' => $price,
        );
        $payForm = $unionPay->goPay($payDetail);

        return $payForm;
    }

    public function finalDoPay($unitePayID = null, $price) {
        include __DIR__ . '/lib/Union/unionpay.php';
        $config = $this->sm->get("Config");
        $unionPay = new \Unionpay($config[getenv('APP_ENV')]['unionPayConfig']);
        $payDetail = array(
            'unitePayID' => $unitePayID,
            'payMoney' => $price,
        );
        $payForm = $unionPay->goPay($payDetail);

        return $payForm;
    }

}
