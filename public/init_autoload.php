<?php

$cookieDomain = str_Replace('www.', '', $_SERVER['SERVER_NAME']);
ini_set("session.cookie_domain", $cookieDomain);
if(defined('ZF2')){
    if(isset($loader)){
        $loader->add('Zend',ZF2);
        $loader->add('COM', LIB);
    }else{


        include ZF2.'/Zend/Loader/AutoloaderFactory.php';
        Zend\Loader\AutoloaderFactory::factory(array(
            'Zend\Loader\StandardAutoloader'    =>  array(
                'autoregister_zf'   =>  true,
                'namespaces' => array(
                    'COM' => LIB . '/COM',
                    'Pingpp' => LIB . '/COM/Service/pingpp-php/lib',
                )
            )
        ));
    }
}

if(!class_exists('Zend\Loader\AutoloaderFactory')){
    throw new RuntimeException('Unable to load ZF2');
}
?>
