<?php
namespace Admin\Controller;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Admin;

class AdController extends Admin{
    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $select = $this->adModel->getSelect();
        $ads = $this->adModel->selectWith($select)->toArray();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('ads' => $ads));
    }

    public function addAction(){
        $adData = array(
            'position' => $this->postData['position']
        );
        $this->adModel->insert($adData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $adID = $this->postData['adID'];
        $update = array();
        $where = array('adID' => $adID);

        $this->adModel->update($update, $where);
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

}
