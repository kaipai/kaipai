<?php
namespace Admin\Controller;

use COM\Controller\Admin;
use Zend\Authentication\Storage\Session;

class IndexController extends Admin{

    public function indexAction(){
        return $this->view;
    }

    public function loginAction(){

        if(!empty($this->postData)){
            $adminName = $this->postData['adminName'];
            $password = $this->postData['password'];
            if($adminName == 'admin' && $password == '123456'){
                $adminLoginInfo = array(
                    'adminName' => $adminName,
                    'password' => $password
                );

                $session = new Session(self::ADMIN_PLATFORM);
                $session->write($adminLoginInfo);
                $this->redirect()->toUrl('/admin/index/index');
            }
        }


        return $this->view;
    }
}
