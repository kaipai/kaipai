<?php
namespace COM\Controller;

use Base\ConstDir\Regexp;
use COM\Controller;

class Front extends Controller{
    protected $version;
    protected $sessionVerifyCode = 'smsPicVerifyCode';
    public function preDispatch()
    {
        parent::preDispatch();
        $this->layout('frontLayout');
        $this->layout()->setVariable('controllerName', $this->controllerName);
        $this->layout()->setVariable('actionName', $this->actionName);
        $this->layout()->setVariable('adminName', $this->adminInfo['username']);
        if($this->controllerName == 'index'){
        }else{
            if($this->checkLogin(self::FRONT_PLATFORM)){

            }else{

            }

        }
    }



    protected function validateMobile($mobile){
        return preg_match(Regexp::MOBILE_VALIDATE, $mobile);
    }
}