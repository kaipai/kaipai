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
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $isIE = 0;
        if(strstr($userAgent, 'MSIE 6') || strstr($userAgent, 'MSIE 7') || strstr($userAgent, 'MSIE 8')){
            $isIE = 1;
        }

        $this->siteSettings = $siteSettings;
        $this->layout()->setVariables(array(
            '_memberInfo' => $this->memberInfo,
            '_categories' => $categories,
            '_siteSettings' => $siteSettings,
            '_isIE' => $isIE,
        ));
        $this->view->setVariables(array(
            '_memberInfo' => $this->memberInfo,
            '_categories' => $categories,
            '_siteSettings' => $siteSettings,
            '_isIE' => $isIE,
        ));



    }



    protected function validateMobile($mobile){
        return preg_match(Regexp::MOBILE_VALIDATE, $mobile);
    }
}