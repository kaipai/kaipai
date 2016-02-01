<?php
namespace Admin\Controller;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Admin;

class MemberController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $members = $this->memberModel->getList();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $members);
    }

    public function addAction(){
        $memberData = $this->postData;
        $this->memberModel->insert($memberData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $memberID = $this->postData['memberID'];
        $set = $this->postData;
        $where = array(
            'memberID' => $memberID
        );
        $this->memberModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }
}
