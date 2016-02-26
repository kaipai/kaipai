<?php
namespace COM\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Regexp;
use COM\Controller;
use Base\ConstDir\Redis;

class Api extends Controller{
    protected $version;

    public function preDispatch()
    {
        parent::preDispatch();

        $this->version = $this->route->getParam('version');

        $this->checkLogin(self::FRONT_PLATFORM);
    }

    public function __get($name){
        $isModel = stripos($name, 'Model');
        $isService = stripos($name, 'Service');
        if($isModel !== false){
            return $this->sm->get('Api\Model\\' . ucfirst(substr($name, 0, -5)));
        }elseif($isService !== false){
            return $this->sm->get('COM\Service\\' . ucfirst($name));
        }
    }

    protected function validateMobile($mobile){
        return preg_match(Regexp::MOBILE_VALIDATE, $mobile);
    }
}