<?php
namespace COM\Controller;

use Base\ConstDir\Regexp;
use COM\Controller;

class Api extends Controller{
    protected $version;
    protected $sessionVerifyCode = 'smsPicVerifyCode';
    public function preDispatch()
    {
        parent::preDispatch();

        $this->version = $this->route->getParam('version');

        $this->checkLogin(self::FRONT_PLATFORM);
    }



    protected function validateMobile($mobile){
        return preg_match(Regexp::MOBILE_VALIDATE, $mobile);
    }
}