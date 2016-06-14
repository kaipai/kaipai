<?php

return array(
    //生产环境配置
    'aliConfig' => array(
        'partner' => '2088601184372385', //商户ID
        'key' => 'ty14ojrq1tfht37ezjlacwbxotoar2yg', //key
        'service' => 'create_direct_pay_by_user',
        'sign_type' => strtoupper('md5'),
        'input_charset' => strtolower('utf-8'),
        'cacert' => LIB . '/COM/Service/PayMod/lib/ali/alipay/cacert.pem',
        'transport' => 'http',
        'notify_url' => 'http://xxx.com/pay/aliNotify',
        'product_notify_url' => 'http://xxx.com/pay/aliProductNotify',
        'special_notify_url' => 'http://xxx.com/pay/aliSpecialNotify',
        'final_notify_url' => 'http://xxx.com/pay/aliFinalNotify',
        'return_url' => 'http://xxx.com/pay/aliReturn',
        'product_return_url' => 'http://xxx.com/pay/aliProductReturn',
        'special_return_url' => 'http://xxx.com/pay/aliSpecialReturn',
        'final_return_url' => 'http://xxx.com/pay/aliFinalReturn',
        'seller_email' => 'lianxian2011510@126.com ',
        'logistics_type' => 'EXPRESS',
        'logistics_fee' => '0.00',
        'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',
    ),
    'unionPayConfig' => array(
        'notify_url' => 'http://xxx.com/pay/unionNotify', //支付后台通知地址
        'product_notify_url' => 'http://xxx.com/pay/aliProductNotify',
        'special_notify_url' => 'http://xxx.com/pay/aliSpecialNotify',
        'final_notify_url' => 'http://xxx.com/pay/aliFinalNotify',
        'return_url' => 'http://xxx.com/pay/unionReturn', //支付前台.......
        'product_return_url' => 'http://xxx.com/pay/unionProductReturn',
        'special_return_url' => 'http://xxx.com/pay/unionSpecialReturn',
        'final_return_url' => 'http://xxx.com/pay/unionFinalReturn',
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
