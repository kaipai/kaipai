<?php
namespace Base\ConstDir;
class WeiXin{

    //*****微信支付进度*****
    const WX_PAY_SCHEDULE_START = "CLIENT_START";   //JS客户端发起
    const WX_PAY_SCHEDULE_ORDER = "SERVER_ORDER";   //服务端统一下单
    const WX_PAY_SCHEDULE_PAY = "CLIENT_PAY";   //客户端支付
    const WX_PAY_SCHEDULE_RESULT = "SERVER_PAY_RESULT";   //服务端支付结果
}