<?php
//默认为test测试环境, product为正式产品环境,除了test测试环境报错以外，其他都不报错test
//
$env= getenv('APP_ENV') ? getenv('APP_ENV') : 'test';
define('BaseRootPath', __DIR__);
chdir(dirname(__DIR__));

if(!defined('ROOT')) {
    define('ROOT', getcwd());
}

define('ZF2', ROOT . "/zf2");
define('LIB', ROOT . '/library');

if($env == "test"){
    putenv('APP_ENV=test');
    error_reporting(E_ALL ^ E_NOTICE);
}elseif($env == "product"){
    error_reporting(0);
}elseif($env == "staging"){
    error_reporting(0);
}else{
    error_reporting(0);
}

require 'init_autoload.php';

Zend\Mvc\Application::init(require ROOT."/configs/main.config.php")->run();
