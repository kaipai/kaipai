<?php

include_once __DIR__ . '/function/common.php';
include_once __DIR__ . '/function/SDKConfig.php';
include_once __DIR__ . '/function/secureUtil.php';
include_once __DIR__ . "/function/httpClient.php";

class Unionpay
{

    private $params = array(
        'version' => '5.0.0', //版本号
        'encoding' => 'utf-8', //编码方式
        'txnType' => '01', //交易类型	
        'txnSubType' => '01', //交易子类
        'bizType' => '000201', //业务类型
        'signMethod' => '01', //签名方法
        'channelType' => '08', //渠道类型，07-PC，08-手机
        'accessType' => '0', //接入类型
        'merId' => '898111453110226', //商户代码，请改自己的测试商户号
        'currencyCode' => '156', //交易币种
    );
    private $request_url;

    function __construct($config)
    {
        $this->params["certId"] = getSignCertId();
        $this->params["txnTime"] = date('YmdHis');
        $this->params["frontUrl"] = $config['return_url'];
        $this->params["backUrl"] = $config["notify_url"];
        $this->request_url = $config["request_uri"];
    }

    function getTN($order)
    {
        $this->params['orderId'] = $order['OrderID'];
        $this->params['txnTime'] = date('YmdHis');
        $this->params['txnAmt'] = $order['OrderPrice'] * 100;
        $this->params['reqReserved'] = base64_encode(json_encode(array('pay_type' => 3)));
        $params = $this->params;
        sign($params);
        $result = sendHttpRequest($params, SDK_App_Request_Url);
        $result_arr = coverStringToArray($result);
//        echo '<pre>';
//        print_r($params);
//        print_r($result_arr);
        if (verify($result_arr)) {
            return $result_arr["tn"];
        } else {
            return false;
        }
    }

    public function Verify($data) {
        if (isset($data['signature'])) {
            return verify($data);
        } else {
            return false;
        }
    }

    public function goPay($payDetail) {

        $this->params["txnAmt"] = $payDetail["payMoney"] * 100;
        $this->params["orderId"] = $payDetail["unitePayID"];
        $params = $this->params;
        sign($params);
        return create_html($params, SDK_FRONT_TRANS_URL);
    }

}
