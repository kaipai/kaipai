<?php


namespace COM\Service\PayMod;


use Base\ConstDir\Api\ApiError;
use Base\ConstDir\BaseConst;

class AliPay extends BasePay
{

    /**
     * 支付
     */
    public function doPay($unitePayID = null) {
        $payDetail = $this->sm->get('Api\Model\MemberPayDetail')->select(array('UnitePayID' => $unitePayID))->current();
        if(empty($payDetail)) throw new \Exception(ApiError::PAY_DETAIL_NOT_EXIST_MSG, ApiError::PAY_DETAIL_NOT_EXIST);

        include_once __DIR__ . '/lib/ali/alipay.php';
        $config = $this->sm->get('Config');
        $aliConfig = $config[getenv('APP_ENV')]['aliConfig'];
        $aliPayment = new \AlipayPayment($aliConfig);
        $subject = BaseConst::ALIPAY_SUBJECT;
        $orderInfo = array(
            "out_trade_no" => $unitePayID, //流水编号       必填
            "subject" => $subject, //订单名称	必填
            "total_fee" => $payDetail['WaitPayMoney'], //付款金额	必填
            "body" => ''
        );
        $payUrl = $aliPayment->get_html_nupost($orderInfo, 'create_direct_pay_by_user');

        return $payUrl;
    }
}
