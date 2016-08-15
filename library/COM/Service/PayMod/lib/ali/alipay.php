<?php

class AlipayPayment {
    /* 支付宝配置 */

    private $_config = array();
    private $alipay_gateway_new = 'https://mapi.alipay.com/gateway.do?';

    public function __construct($payment_config) {
        $this->_config = $payment_config;
    }

    /**
     * 获取支付代码
     *
     * @author freeway
     * @param array $order_info  待支付的订单信息
     * @param $service
     * @return string
     */
    public function get_html_nupost($order_info, $service = 'create_direct_pay_by_user') {
        // 构造要请求的参数数组
        if ($service == 'create_direct_pay_by_user') {
            $parameter = array(
                // 基本参数
                "service" => "create_direct_pay_by_user", //即时到帐
                "partner" => trim($this->_config['partner']),
                "payment_type" => '1',
                "notify_url" => $this->_config['notify_url'],
                "return_url" => $this->_config['return_url'],
                "seller_email" => $this->_config['seller_email'], //签约支付宝账号或卖家支付宝帐户
                "_input_charset" => 'utf-8', //字符编码格式 目前支持 GBK 或 utf-8
                // 业务参数
                "out_trade_no" => $order_info['out_trade_no'], //订单编号 必填
                "subject" => $order_info['subject'], //订单名称	必填
                "total_fee" => $order_info['total_fee'], //付款金额	必填
                "body" => $order_info['body'], //订单描述
                "show_url" => $order_info['show_url'], //商品展示地址
                "anti_phishing_key" => '', //防钓鱼时间戳
                "exter_invoke_ip" => '', //客户端的IP地址
            );
        } else {
            $parameter = array(
                "service" => "create_partner_trade_by_buyer",
                "partner" => trim($this->_config['partner']),
                "payment_type" => '1',
                "notify_url" => $this->_config['notify_url'],
                "return_url" => $this->_config['return_url'],
                "seller_email" => $this->_config['seller_email'],
                "out_trade_no" => $order_info['out_trade_no'],
                "subject" => $order_info['subject'],
                "price" => $order_info['total_fee'],
                "quantity" => '1',
                "logistics_fee" => $this->_config['logistics_fee'],
                "logistics_type" => $this->_config['logistics_type'],
                "logistics_payment" => $this->_config['logistics_payment'],
                "body" => $order_info['body'],
                "show_url" => '',
                "receive_name" => '高速',
                "receive_address" => '浙江杭州江干区九堡',
                "receive_zip" => '310000',
                "receive_phone" => '',
                "receive_mobile" => '13758227340',
                "_input_charset" => 'utf-8'
            );
        }

        require_once("alipay/alipay_submit.class.php");
        $alipaySubmit = new AlipaySubmit($this->_config);
        $html = $alipaySubmit->buildRequestParaToString($parameter, "get", "确认");
        return $this->alipay_gateway_new . $html;
    }

    /**
     * 获取支付代码
     *
     * @author andery
     * @param array $order_info  待支付的订单信息
     * @return string
     */
    public function get_html($order_info) {
        // 构造要请求的参数数组
        $parameter = array(
            // 基本参数
//         		"service" 			=> "create_direct_pay_by_user",	//即时到帐
            "service" => "create_direct_pay_by_user", //即时到帐
            "partner" => trim($this->_config['partner']),
            "payment_type" => '1',
            "notify_url" => $this->_config['notify_url'],
            "return_url" => $this->_config['return_url'],
            "seller_email" => $this->_config['seller_email'], //签约支付宝账号或卖家支付宝帐户
            "_input_charset" => 'utf-8', //字符编码格式 目前支持 GBK 或 utf-8
            // 业务参数
            "out_trade_no" => $order_info['order_id'], //订单编号 必填
            "subject" => $order_info['subject'], //订单名称	必填
            "total_fee" => $order_info['total_fee'], //付款金额	必填
            "body" => $order_info['body'], //订单描述
            "show_url" => $order_info['show_url'], //商品展示地址
            "anti_phishing_key" => '', //防钓鱼时间戳
            "exter_invoke_ip" => '', //客户端的IP地址
        );

        require_once("alipay/alipay_submit.class.php");
        $alipaySubmit = new AlipaySubmit($this->_config);
        $html = $alipaySubmit->buildRequestForm($parameter, "get", "确认");
        return $html;
    }

    public function returnVerify() {
        require_once("alipay/alipay_notify.class.php");
        $alipayNotify = new AlipayNotify($this->_config);
        $verify_result = $alipayNotify->verifyReturn();
        //验证
        if ($verify_result) {
            return $_GET;
        } else {
            return false;
        }
    }

    public function notifyVerify() {
        require_once("alipay/alipay_notify.class.php");
        $alipayNotify = new AlipayNotify($this->_config);
        $verify_result = $alipayNotify->verifyNotify();
        //验证
        if ($verify_result) {
            return $_POST;
        } else {
            return false;
        }
    }

}