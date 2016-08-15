<?php
namespace Admin\Controller;
use Base\ConstDir\Admin\AdminError;
use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class AdminController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $offset = $this->request->getQuery('offset', $this->offset);
        $limit = $this->request->getQuery('limit', $this->limit);
        $where = array();
        $username = $this->request->getQuery("username");

        if(!empty($username)){
            $where['Username like ?'] = "%".$username."%";
        }

        $data = array();

        $data['total'] = $this->adminModel->getCount($where);

        $data['rows'] = $this->adminModel->getList($where, "AdminID desc", $offset, $limit);

        return $this->adminResponse($data);
    }

    public function addAction(){
        $username = $this->request->getPost("username");
        $passwd = $this->request->getPost("passwd");
        if(empty($username) || empty($passwd)){
            return $this->response(AdminError::PARAMETER_MISSING, AdminError::PARAMETER_MISSING_MSG);
        }
        if(strlen($passwd) < 6){
            return $this->response(AdminError::PASSWD_LENGTH_LT_SIX, AdminError::PASSWD_LENGTH_LT_SIX_MSG);
        }

        $data = array();
        $data['username'] = $username;
        $data['passwd'] = $this->adminModel->genPassword($passwd);
        try{
            $this->adminModel->insert($data);
            return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
        }catch (\Exception $e){
            return $this->response(AdminError::DATA_INSERT_FAILED, AdminError::DATA_INSERT_FAILED_MSG);
        }
    }

    public function getAction(){
        $adminID = $this->request->getPost('adminID');
        $adminInfo = $this->adminModel->select(array('adminID' => $adminID))->current();

        return $this->adminResponse($adminInfo);
    }

    public function updateAction(){
        $newPasswd = $this->request->getPost("passwd");
        $adminID = $this->request->getPost("adminID");
        if(empty($newPasswd) || empty($adminID)) return $this->response(AdminError::PARAMETER_MISSING, AdminError::PARAMETER_MISSING_MSG);
        if(strlen($newPasswd) < 6){
            return $this->response(AdminError::PASSWD_LENGTH_LT_SIX, AdminError::PASSWD_LENGTH_LT_SIX_MSG);
        }

        $updateData = array();
        if(!empty($newPasswd)){
            $updateData['passwd'] = $this->adminModel->genPassword($newPasswd);
        }
        try{
            $this->adminModel->update($updateData,array("adminID" => $adminID));
            return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
        }catch (\Exception $e){
            return $this->response(AdminError::DATA_UPDATE_FAILED, AdminError::DATA_UPDATE_FAILED_MSG);
        }
    }

    public function delAction(){

        $adminID = $this->request->getPost("adminID");
        if(empty($adminID)){
            return $this->response(AdminError::PARAMETER_MISSING, AdminError::PARAMETER_MISSING_MSG);
        }

        try{
            $this->adminModel->delete(array("adminID" => $adminID));
            return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
        }catch (\Exception $e){
            return $this->response(AdminError::DATA_DELETE_FAILED, AdminError::DATA_DELETE_FAILED_MSG);
        }
    }
}