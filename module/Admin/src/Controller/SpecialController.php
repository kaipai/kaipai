<?php
namespace Admin\Controller;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Admin;

class SpecialController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $specials = $this->specialModel->getList();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $specials);
    }

    public function addAction(){
        $specialData = $this->postData;
        $this->specialModel->insert($specialData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $specialID = $this->postData['specialID'];
        $set = $this->postData;
        $where = array(
            'specialID' => $specialID
        );
        $this->specialModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

}
