<?php

namespace COM\Service\PayMod;

use Base\ConstDir\Api\ApiError;

class MerchantPay extends BasePay
{

    public function doPay($unitePayID = null) {
        if(empty($unitePayID)) throw new \Exception(ApiError::PARAMETER_MISSING_MSG, ApiError::PARAMETER_MISSING);

        $payDetail = $this->sm->get('Api\Model\MemberPayDetail')->select(array('UnitePayID' => $unitePayID))->current();
        if (empty($payDetail)) throw new \Exception(ApiError::PAY_DETAIL_NOT_EXIST_MSG, ApiError::PAY_DETAIL_NOT_EXIST);

        $order['SerialNum'] = $unitePayID;              //订单号16位
        $order['OrderPrice'] = $payDetail['WaitPayMoney'];              //金额
        $form = $this->generateForm($order);
        $form .= "<script>document.getElementById('fm').submit()</script>";
        return $form;
    }

    public function generateForm($order) {
        $configs = $this->sm->get("config");
        $config = $configs[getenv('APP_ENV')]['cmb'];
        $date = date('Ymd');
        $para = $order["SerialNum"];
        $billno = strlen($order['SerialNum']) > 10 ? substr($order['SerialNum'], -10) : $order['SerialNum'];
        $form = <<<Form
<html>
<head>
</head>
<body>
<form id="fm" action="{$config['gateWayUrl']}" method="post">
<input name="branchid" value="{$config['BranchID']}" type="hidden">
<input name="MfcISAPICommand" value="PrePayWAP" type="hidden">
<input name="cono" value="{$config['CoNo']}" type="hidden">
<input name="MerchantUrl" value="{$config['MerchantUrl']}" type="hidden">
<input name="date" value="{$date}" type="hidden">
<input name="billno" value="{$billno}" type="hidden">
<input name="MerchantPara" value='$para' type="hidden">
<input name="amount" type="hidden" value="{$order['OrderPrice']}">
</form>
</body>
</html>
Form;
        return $form;
    }

    public function checkSign($query)
    {

        include __DIR__ . '/java/Java.inc';
        java_set_file_encoding("GBK");

        $client = new \Java("cmb.netpayment.Security", ROOT . "/configs/public.key");
        $getmsg = new \Java("java.lang.String", $query);
        $ret = $client->checkInfoFromBank($getmsg);
        $ret = java_values($ret);
        return $ret;
    }
}
