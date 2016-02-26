<?php
namespace Api\Controller\v1;

use COM\Controller;

class PingppController extends Controller{

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