<?php
namespace COM\Controller;

use Base\ConstDir\Api\ApiError;
use COM\Controller;
use Base\ConstDir\Redis;

class Api extends Controller{
    protected $version;

    public function preDispatch()
    {
        parent::preDispatch();

        $this->version = $this->route->getParam('version');

        $signature = strtolower($this->postData['Signature']);
        if(empty($signature) || (getenv('APP_ENV') != 'test' && $signature != $this->genSignature())){
        }
    }

    public function __get($name){
        $isModel = stripos($name, 'Model');
        $isService = stripos($name, 'Service');
        if($isModel !== false){
            return $this->sm->get('Api\Model\\' . ucfirst(substr($name, 0, -5)));
        }elseif($isService !== false){
            return $this->sm->get('COM\Service\\' . ucfirst(substr($name, 0, -7)));
        }
    }
}