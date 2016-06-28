<?php


namespace COM\Service\PayMod;


use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\BaseConst;
use Base\Functions\Utility;

class WxPay extends BasePay
{
    private $sm = '';
    private $appid = 'wx42eaae1f8572ba8e';  //支付appid
    private $orderUrl = 'https://api.mch.weixin.qq.com/pay/unifiedorder';   //微信统一下单地址
    private $key = 'c55459626311ab62f2c71b03487f7d7d';  //支付秘钥
    private $mch_id = '1268988901';  //商户号
    private $trade_type = 'NATIVE';  //支付类型
    private $nonceStrLength = 32; //随机数成成位数
    private $notifyUrl = "http://www.kaipai123.com/pay/wx-notify"; //异步通知地址
    private $spbill_create_ip = '114.55.36.109';
    /**
     * 支付
     */
    public function doPay($unitePayID = null, $price) {
        $data = array(
            'body' => '收款',
            'out_trade_no' => $unitePayID,
            'total_fee' => $price * 100,
        );

        $return = $this->unifiedOrder($data);
        if($return['return_code'] == 'SUCCESS' && $return['result_code'] == 'SUCCESS'){
            $codePath = $this->sm->get('COM\Service\QrcodeService')->png($return['code_url']);
            return $codePath;
        }else{
            throw new \Exception($return['return_msg'], ApiError::COMMON_ERROR);
        }
    }

    public function productDoPay($unitePayID = null, $price) {
        $data = array(
            'body' => '收款',
            'out_trade_no' => $unitePayID,
            'total_fee' => $price * 100,
        );
        $this->notifyUrl = 'http://www.kaipai123.com/pay/wx-product-notify';
        $return = $this->unifiedOrder($data);
        if($return['return_code'] == 'SUCCESS' && $return['result_code'] == 'SUCCESS'){
            $codePath = $this->sm->get('COM\Service\QrcodeService')->png($return['code_url']);
            return $codePath;
        }else{
            throw new \Exception($return['return_msg'], ApiError::COMMON_ERROR);
        }
    }

    public function specialDoPay($unitePayID = null, $price) {
        $data = array(
            'body' => '收款',
            'out_trade_no' => $unitePayID,
            'total_fee' => $price * 100,
        );
        $this->notifyUrl = 'http://www.kaipai123.com/pay/wx-special-notify';
        $return = $this->unifiedOrder($data);
        if($return['return_code'] == 'SUCCESS' && $return['result_code'] == 'SUCCESS'){
            $codePath = $this->sm->get('COM\Service\QrcodeService')->png($return['code_url']);
            return $codePath;
        }else{
            throw new \Exception($return['return_msg'], ApiError::COMMON_ERROR);
        }
    }


    public function createRand($length){
        $randLetter = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
            'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's',
            't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D',
            'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O',
            'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z',
            1,2,3,4,5,6,7,8,9,0);
        $str = '';
        for($i = 0;$i < $length;$i++){
            $str .= $randLetter[rand(0,61)];
        }
        return $str;
    }

    public function unifiedOrder($data = array()){
        $data['appid'] = $this->appid;
        $data['nonce_str'] = $this->createRand($this->nonceStrLength);
        $data['fee_type'] = "CNY";
        $data['mch_id'] = $this->mch_id;//商户号
        $data['trade_type'] = $this->trade_type;//商户号
        $data['time_start'] = date("YmdHis",time());   //订单开始时间
        $data['time_expire'] = date("YmdHis",time()+300);   //订单失效时间
        $data['sign'] = $this->makeSign($data);
        $data['notify_url'] = $this->notifyUrl;
        $data['spbill_create_ip'] = $this->spbill_create_ip;
        $data = Utility::toXml($data);
        $result = $this->postXmlCurl($data, $this->orderUrl);
        $result = Utility::decodeXml($result);

        return $result;
    }

    public function makeSign($data){
        //签名步骤一：按字典序排序参数
        ksort($data);
        $string = Utility::toUrlParams($data);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=". $this->key;
        //var_dump($string);
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }


    public function postXmlCurl($xml, $url, $useCert = false, $second = 30){
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);


        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            return false;
        }
    }


}
