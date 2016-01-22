<?php
namespace Admin\Controller;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Admin;

class MemberController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $select = $this->memberModel->getSelect();
        $members = $this->memberModel->selectWith($select)->toArray();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('members' => $members));
    }

    public function addAction(){
        $memberData = array(

        );
        $this->memberModel->insert($memberData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $memberID = $this->postData['memberID'];
        $set = array();
        $where = array(
            'memberID' => $memberID
        );
        $this->memberModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }
}
