<?php

namespace COM\Service\PayMod;



class UnionPay extends BasePay
{

    public function doPay($unitePayID = null, $price) {

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
