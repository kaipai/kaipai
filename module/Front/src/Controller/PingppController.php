<?php
namespace Front\Controller;

use COM\Controller\Front;

class PingppController extends Front{

    public function testAction(){
        /*{
            "channel":"alipay_wap",
            "amount":10,
            "order_no":"no1234567890",
            "open_id":"",
            "a":1,
            "b":2
        }*/
        $url = \Pingpp\WxpubOAuth::createOauthUrlForCode($wx_app_id, $redirect_url);
        var_dump($url);
        die('333');
    }
}