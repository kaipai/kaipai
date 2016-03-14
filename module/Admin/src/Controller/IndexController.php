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
            $where = array(
                'username' => $adminName,
                'passwd' => $this->adminModel->genPassword($password),
            );
            $adminLoginInfo = $this->adminModel->select($where)->current();
            if(!empty($adminLoginInfo)){
                $session = new Session(self::ADMIN_PLATFORM);
                $session->write($adminLoginInfo);
                return $this->redirect()->toUrl('/admin/index/index');
            }else{
                $this->view->setVariable('error', '用户名或密码错误');
            }

        }


        return $this->view;
    }

    public function logoutAction(){
        $session = new Session(self::ADMIN_PLATFORM);
        $session->clear();
        return $this->redirect()->toUrl('/admin/index/login');
    }

    public function uploadAction(){
        $fileService = $this->sm->get('COM\Service\FileService');
        $result = $fileService->upload($this->request);
        if(!empty($result)) $result = json_decode($result, true);
        $imgPath = !empty($result[0]['path']) ? $result[0]['path'] : '';

        $data['path'] = $imgPath;

        return $this->adminResponse($data);
    }
}
