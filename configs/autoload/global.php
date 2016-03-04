<?php

return array(
    //开发环境配置
    'test'   =>  array(
        'domain'=>"http://xxx.com",
        'cmb'    =>  array(
            'BranchID' => '0571',
            'CoNo' => '036429',
            'gateWayUrl' => 'https://netpay.cmbchina.com/netpayment/basehttp.dll?TestPrePayC2',
            'MerchantUrl' => 'http://newapp.chemayi.com/api/v1/pay/merchantNotify'
        ),
        'aliConfig' => array(
            'partner' => '2088601184372385', //商户ID
            'key' => 'ty14ojrq1tfht37ezjlacwbxotoar2yg', //key
            'service' => 'create_direct_pay_by_user',
            'sign_type' => strtoupper('md5'),
            'input_charset' => strtolower('utf-8'),
            'cacert' => LIB . '/COM/Service/PayMod/lib/ali/alipay/cacert.pem',
            'transport' => 'http',
            'notify_url' => 'http://newapp.chemayi.com/api/v1/pay/aliNotify',
            'return_url' => 'http://newapp.chemayi.com/api/v1/pay/aliReturn',
            'seller_email' => 'lianxian2011510@126.com ',
            'logistics_type' => 'EXPRESS',
            'logistics_fee' => '0.00',
            'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',
        ),
        'unionPayConfig' => array(
            'notify_url' => 'http://newapp.chemayi.com/api/v1/pay/unionNotify', //支付后台通知地址
            'return_url' => 'http://newapp.chemayi.comm/api/v1/pay/unionReturn', //支付前台.......
        ),
        'bd-config'=>array(
            'and'=>array(
                'ak'=>'I6ph4WU2mrAAAevQN0Kv9Qac',
                'sk'=>'xQGIP0OfCrkxMHuilPowQGYOZyuExGVY',
            ),

            'ios'=>array(
                'ak'=>'uRd26IGZDo03X7WY4P37E3U7',
                'sk'=>'dLem844jFCRvLBypblFlLmG1H6mP6ufn',
            ),
        ),
    ),
    //staging配置
    'staging'   =>  array(
        'domain'=>"http://newapp.chemayi.com",
        'cmb'    =>  array(
            'BranchID' => '0571',
            'CoNo' => '036429',
            'gateWayUrl' => 'https://netpay.cmbchina.com/netpayment/basehttp.dll',
            'MerchantUrl' => 'http://newapp.chemayi.com/api/v1/pay/merchantNotify'
        ),
        'aliConfig' => array(
            'partner' => '2088601184372385', //商户ID
            'key' => 'ty14ojrq1tfht37ezjlacwbxotoar2yg', //key
            'service' => 'create_direct_pay_by_user',
            'sign_type' => strtoupper('md5'),
            'input_charset' => strtolower('utf-8'),
            'cacert' => LIB . '/COM/Service/PayMod/lib/ali/alipay/cacert.pem',
            'transport' => 'http',
            'notify_url' => 'http://newapp.chemayi.com/api/v1/pay/aliNotify',
            'return_url' => 'http://newapp.chemayi.com/api/v1/pay/aliReturn',
            'seller_email' => 'lianxian2011510@126.com ',
            'logistics_type' => 'EXPRESS',
            'logistics_fee' => '0.00',
            'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',
        ),
        'unionPayConfig' => array(
            'notify_url' => 'http://newapp.chemayi.com/api/v1/pay/unionNotify', //支付后台通知地址
            'return_url' => 'http://newapp.chemayi.com/api/v1/pay/unionReturn', //支付前台.......
        ),
        'bd-config'=>array(
            'and'=>array(
                'ak'=>'I6ph4WU2mrAAAevQN0Kv9Qac',
                'sk'=>'xQGIP0OfCrkxMHuilPowQGYOZyuExGVY',
            ),
            'ios'=>array(
                'ak'=>'uRd26IGZDo03X7WY4P37E3U7',
                'sk'=>'dLem844jFCRvLBypblFlLmG1H6mP6ufn',
            ),
        ),
    ),
    //生产环境配置
    'product'   =>  array(
        'domain'=>"http://xxx.chemayi.com",
        'cmb'    =>  array(
            'BranchID' => '0571',
            'CoNo' => '036429',
            'gateWayUrl' => 'https://netpay.cmbchina.com/netpayment/basehttp.dll',
            'MerchantUrl' => 'http://xxx.com/api/v1/pay/merchantNotify'
        ),
        'aliConfig' => array(
            'partner' => '2088601184372385', //商户ID
            'key' => 'ty14ojrq1tfht37ezjlacwbxotoar2yg', //key
            'service' => 'create_direct_pay_by_user',
            'sign_type' => strtoupper('md5'),
            'input_charset' => strtolower('utf-8'),
            'cacert' => LIB . '/COM/Service/PayMod/lib/ali/alipay/cacert.pem',
            'transport' => 'http',
            'notify_url' => 'http://xxx.com/api/v1/pay/aliNotify',
            'return_url' => 'http://xxx.com/api/v1/pay/aliReturn',
            'wap_return_url' => 'http://mall.chemayi.com/sucpage',
            'seller_email' => 'lianxian2011510@126.com ',
            'logistics_type' => 'EXPRESS',
            'logistics_fee' => '0.00',
            'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',
        ),
        'unionPayConfig' => array(
            'notify_url' => 'http://xxx.com/api/v1/pay/unionNotify', //支付后台通知地址
            'return_url' => 'http://xxx.com/api/v1/pay/unionReturn', //支付前台.......
            'wap_return_url' => 'http://xxx.com/sucpage',
        ),
        'bd-config'=>array(
            'and'=>array(
                'ak'=>'I6ph4WU2mrAAAevQN0Kv9Qac',
                'sk'=>'xQGIP0OfCrkxMHuilPowQGYOZyuExGVY',
            ),
            'ios'=>array(
                'ak'=>'uRd26IGZDo03X7WY4P37E3U7',
                'sk'=>'dLem844jFCRvLBypblFlLmG1H6mP6ufn',
            ),
        ),
    ),
    'db' => array(
        'driver' => 'Pdo',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'app-super-verify-code' => '059814',
    'response-signature-code' => 'zxvjequi@#!',
    /**
     * @description 短信发送配置
     */
    'sms'   =>  array(
        'type'  =>  2,//1-螺丝帽发送短信，2-云片发送短信
        'luosimao'   =>array(
            'name' => '螺丝帽',
        ),
        'yunpian'   =>  array(
            'url' => 'http://yunpian.com/v1/sms/send.json',
            'apikey' => '07ff99c0e23377493fb218e9c63c7fa6',
            'name' => '云片',
        ),
    ),

    'file-cache-config' => array(
        'cacheDir'=>ROOT.'/module/Base/FileCacheDir',
        'namespace'=>'com',
        'dirLevel'=>0
    ),
);

/**
 * local config
 */
/*return array(
    'db' => array(
        'username' => 'cheguanjia',
        'password' => '123456',
        'dsn'=> 'mysql:dbname=cheguanjia;host=192.168.1.200',
    ),
    'redis' => array(
        'host' => '192.168.1.200',
        'port' => '6379',
        'namespace' => 'com',
        'auth' => 'chemayi123!@#',
    )
);*/
