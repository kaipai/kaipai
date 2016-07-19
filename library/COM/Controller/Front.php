<?php
namespace COM\Controller;

use Base\ConstDir\Regexp;
use COM\Controller;
use Zend\Authentication\Storage\Session;

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

        if(!empty($this->memberInfo) && !$this->memberInfoModel->isAvailable($this->memberInfo)){
            $session = new Session(self::FRONT_PLATFORM);
            $session->clear();
            setcookie('autoCode', '', time(), '/');
            return $this->redirect()->toUrl('/login/do-login');
        }
        if(!empty($this->memberInfo)){
            if($this->controllerName == 'member' && $this->actionName == 'notification'){
                $this->memberInfo['notReadCount'] = 0;
            }else{
                $this->memberInfo['notReadCount'] = $this->notificationModel->getCount(array('memberID' => $this->memberInfo['memberID'], 'read' => 0));
            }

        }




        $categories = $this->productCategoryModel->select()->toArray();

        $tmp = $this->siteSettingModel->select()->toArray();
        $siteSettings = array();
        foreach($tmp as $v){
            if($v['fieldType'] == 'simpleTextArea'){
                $v['settingValue'] = str_replace(array("\n", "\r\n"), '<br />', $v['settingValue']);
            }
            $siteSettings[$v['settingName']] = $v['settingValue'];
        }
        $qq = mt_rand(1, 3);
        if($qq == 2 && !empty($siteSettings['qq2'])){
            $siteSettings['qq'] = $siteSettings['qq2'];
        }elseif($qq == 3 && !empty($siteSettings['qq3'])){
            $siteSettings['qq'] = $siteSettings['qq3'];
        }
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $isIE = 0;
        $isLowIE = 0;
        if(strstr($userAgent, 'MSIE 6') || strstr($userAgent, 'MSIE 7') || strstr($userAgent, 'MSIE 8')){
            if(strstr($userAgent, 'MSIE 6') || strstr($userAgent, 'MSIE 7')){
                $isLowIE = 1;
            }
            $isIE = 1;
        }

        $this->siteSettings = $siteSettings;
        $this->layout()->setVariables(array(
            '_memberInfo' => $this->memberInfo,
            '_categories' => $categories,
            '_siteSettings' => $siteSettings,
            '_isIE' => $isIE,
            '_isLowID' => $isLowIE,
        ));
        $this->view->setVariables(array(
            '_memberInfo' => $this->memberInfo,
            '_categories' => $categories,
            '_siteSettings' => $siteSettings,
            '_isIE' => $isIE,
            '_isLowID' => $isLowIE,
            '_actionName' => $this->actionName,
        ));



    }



    protected function validateMobile($mobile){
        return preg_match(Regexp::MOBILE_VALIDATE, $mobile);
    }
}