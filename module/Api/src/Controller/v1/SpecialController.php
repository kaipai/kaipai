<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Api;

class SpecialController extends Api{

    public function listAction(){
        $where = array();
        $specials = $this->specialModel->getList($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $specials);

    }

    public function detailAction(){
        $where = array(
            'specialID' => $this->postData['specialID']
        );
        $specialInfo = $this->specialModel->fetch($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $specialInfo);
    }

    public function addAction(){
        $data = $this->postData;

        $this->specialModel->insert($data);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }
}