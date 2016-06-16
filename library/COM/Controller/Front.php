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
        if($this->request->isXmlHttpRequest()){
            $this->view->setNoLayout();
        }else{
            if(in_array($this->controllerName, array('member', 'zone'))){
                $this->layout('frontMemberLayout');
            }else{
                $this->layout('frontLayout');
            }
        }


        $this->layout()->setVariable('controllerName', $this->controllerName);
        $this->layout()->setVariable('actionName', $this->actionName);
        $this->layout()->setVariable('adminName', $this->adminInfo['username']);

        $this->checkLogin(self::FRONT_PLATFORM);

        $categories = $this->productCategoryModel->select()->toArray();

        $tmp = $this->siteSettingModel->select()->toArray();
        $siteSettings = array();
        foreach($tmp as $v){
            $siteSettings[$v['settingName']] = $v['settingValue'];
        }
        $this->siteSettings = $siteSettings;
        $this->layout()->setVariables(array(
            '_memberInfo' => $this->memberInfo,
            '_categories' => $categories,
            '_siteSettings' => $siteSettings,
        ));
        $this->view->setVariables(array(
            '_memberInfo' => $this->memberInfo,
            '_categories' => $categories,
            '_siteSettings' => $siteSettings,
        ));



    }



    protected function validateMobile($mobile){
        return preg_match(Regexp::MOBILE_VALIDATE, $mobile);
    }
}