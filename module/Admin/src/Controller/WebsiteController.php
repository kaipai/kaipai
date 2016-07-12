<?php
namespace Admin\Controller;
use Base\ConstDir\Admin\AdminError;
use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class WebsiteController extends Admin{
    public function indexAction(){
        $settings = $this->siteSettingModel->getSettings();
        foreach($settings as $k => $v){
            if($v['fieldType'] == 'select' && !empty($v['options'])){
                $settings[$k]['options'] = explode(',', $v['options']);
            }elseif($v['fieldType'] == 'multipleImg' && !empty($v['settingValue'])){
                $settings[$k]['settingValue'] = json_decode($v['settingValue'], true);
            }
        }

        $this->view->setVariables(array(
            'settings' => $settings,
        ));
        return $this->view;
    }

    public function saveSettingsAction(){
        $settings = $this->siteSettingModel->getSettings();
        foreach($settings as $v){
            if(!empty($this->postData[$v['settingName']]) || $this->postData[$v['settingName']] === '0' || $this->postData[$v['settingName']] === 0){
                if($v['fieldType'] == 'multipleImg'){
                    $this->postData[$v['settingName']] = json_encode($this->postData[$v['settingName']]);
                }
                $this->siteSettingModel->update(array('settingValue' => $this->postData[$v['settingName']]), array('settingName' => $v['settingName']));
            }
        }

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');
    }

    public function setWithdrawPwdAction(){
        $mobile = $this->postData['mobile'];
        $verifyCode = $this->postData['verifyCode'];
        $password = $this->postData['password'];
        $confirmPassword = $this->postData['confirmPassword'];

        if(empty($mobile) || empty($verifyCode) || empty($password) || empty($confirmPassword)) return $this->response(AdminError::COMMON_ERROR, '缺少参数');

        if($password != $confirmPassword){
            return $this->response(AdminError::COMMON_ERROR, '两次输入密码不一致');
        }
        $smsVeriyfCode = $this->mobileVerifyCodeModel->getLastVerifyCode($mobile);
        if($verifyCode == $smsVeriyfCode){
            try{
                $this->siteSettingModel->update(array('settingValue' => $password), array('settingName' => 'withdrawPassword'));
                return $this->response(AdminSuccess::COMMON_SUCCESS, '提现密码重置成功');
            }catch (\Exception $e){
                return $this->response(AdminError::COMMON_ERROR, '重置失败, 请重试');
            }
        }else{
            return $this->response(AdminError::COMMON_ERROR, '手机验证码验证失败');
        }

    }
}