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
            if(!empty($this->postData[$v['settingName']]) || $this->postData[$v['settingName']] === 0){
                if($v['fieldType'] == 'multipleImg'){
                    $this->postData[$v['settingName']] = json_encode($this->postData[$v['settingName']]);
                }
                $this->siteSettingModel->update(array('settingValue' => $this->postData[$v['settingName']]), array('settingName' => $v['settingName']));
            }
        }

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');
    }
}