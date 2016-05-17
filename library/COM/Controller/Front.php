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
        if($this->controllerName == 'member'){
            $this->layout('frontMemberLayout');
        }else{
            $this->layout('frontLayout');
        }

        $this->layout()->setVariable('controllerName', $this->controllerName);
        $this->layout()->setVariable('actionName', $this->actionName);
        $this->layout()->setVariable('adminName', $this->adminInfo['username']);

        $this->checkLogin(self::FRONT_PLATFORM);

        $categories = $this->productCategoryModel->select()->toArray();

        $this->layout()->setVariables(array(
            '_memberInfo' => $this->memberInfo, '_categories' => $categories,
        ));
        $this->view->setVariables(array(
            '_memberInfo' => $this->memberInfo
        ));

    }



    protected function validateMobile($mobile){
        return preg_match(Regexp::MOBILE_VALIDATE, $mobile);
    }
}