<?php
namespace COM\Controller;

use COM\Controller;

class Admin extends Controller{

    public function preDispatch()
    {
        parent::preDispatch();
        if($this->controllerName == 'index' && $this->actionName == 'login'){
            $this->view->setNoLayout();
        }else{
            if($this->checkLogin(self::ADMIN_PLATFORM)){
                $this->layout('adminLayout');
                $this->layout()->setVariable('controllerName', $this->controllerName);
                $this->layout()->setVariable('actionName', $this->actionName);
                $this->layout()->setVariable('adminName', $this->adminInfo['username']);
            }else{
                $this->redirect()->toUrl('/admin/index/login');
            }

        }

    }
}