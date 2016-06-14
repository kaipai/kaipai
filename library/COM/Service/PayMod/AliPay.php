<?php


namespace COM\Service\PayMod;


use Base\ConstDir\Api\ApiError;
use Base\ConstDir\BaseConst;

class AliPay extends BasePay
{

    /**
     * 支付
     */
    public function doPay($unitePayID = null, $price) {
        include_once __DIR__ . '/lib/ali/alipay.php';
        $config = $this->sm->get('Config');
        $aliConfig = $config[getenv('APP_ENV')]['aliConfig'];
        $aliPayment = new \AlipayPayment($aliConfig);
        $subject = '收款';
        $orderInfo = array(
            "out_trade_no" => $unitePayID, //流水编号       必填
            "subject" => $subject, //订单名称	必填
            "total_fee" => $price, //付款金额	必填
            "body" => ''
        );
        $payUrl = $aliPayment->get_html_nupost($orderInfo, 'create_direct_pay_by_user');

        return $payUrl;
    }

    public function productDoPay($unitePayID = null, $price) {
        include_once __DIR__ . '/lib/ali/alipay.php';
        $config = $this->sm->get('Config');
        $aliConfig = $config['aliConfig'];
        $aliConfig['notify_url'] = $aliConfig['product_notify_url'];
        $aliConfig['return_url'] = $aliConfig['product_return_url'];
        $aliPayment = new \AlipayPayment($aliConfig);
        $subject = '发布拍品收费';
        $orderInfo = array(
            "out_trade_no" => $unitePayID, //流水编号       必填
            "subject" => $subject, //订单名称	必填
            "total_fee" => $price, //付款金额	必填
            "body" => ''
        );
        $payUrl = $aliPayment->get_html_nupost($orderInfo, 'create_direct_pay_by_user');

        return $payUrl;
    }

    public function specialDoPay($unitePayID = null, $price) {
        include_once __DIR__ . '/lib/ali/alipay.php';
        $config = $this->sm->get('Config');
        $aliConfig = $config[getenv('APP_ENV')]['aliConfig'];
        $aliConfig['notify_url'] = $aliConfig['special_notify_url'];
        $aliConfig['return_url'] = $aliConfig['special_return_url'];
        $aliPayment = new \AlipayPayment($aliConfig);
        $subject = '发布专场收费';
        $orderInfo = array(
            "out_trade_no" => $unitePayID, //流水编号       必填
            "subject" => $subject, //订单名称	必填
            "total_fee" => $price, //付款金额	必填
            "body" => ''
        );
        $payUrl = $aliPayment->get_html_nupost($orderInfo, 'create_direct_pay_by_user');

        return $payUrl;
    }

    public function finalDoPay($unitePayID = null, $price) {
        include_once __DIR__ . '/lib/ali/alipay.php';
        $config = $this->sm->get('Config');
        $aliConfig = $config[getenv('APP_ENV')]['aliConfig'];
        $aliConfig['notify_url'] = $aliConfig['final_notify_url'];
        $aliConfig['return_url'] = $aliConfig['final_return_url'];
        $aliPayment = new \AlipayPayment($aliConfig);
        $subject = '支付尾款';
        $orderInfo = array(
            "out_trade_no" => $unitePayID, //流水编号       必填
            "subject" => $subject, //订单名称	必填
            "total_fee" => $price, //付款金额	必填
            "body" => ''
        );
        $payUrl = $aliPayment->get_html_nupost($orderInfo, 'create_direct_pay_by_user');

        return $payUrl;
    }
}
