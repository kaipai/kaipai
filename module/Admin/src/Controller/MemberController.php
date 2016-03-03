<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class MemberController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $offset = $this->request->getQuery('offset', $this->offset);
        $limit = $this->request->getQuery('limit', $this->limit);
        $where = array();

        $data = array();

        $data['total'] = $this->memberInfoModel->getCount($where);

        $data['rows'] = $this->memberInfoModel->getList($where, "memberID desc", $offset, $limit);

        return $this->adminResponse($data);
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
