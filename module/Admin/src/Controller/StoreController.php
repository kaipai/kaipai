<?php
namespace Admin\Controller;

use COM\Controller\Admin;
use Base\ConstDir\BaseConst;
use Base\ConstDir\Api\ApiSuccess;

class StoreController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $stores = $this->storeModel->getList();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $stores);
    }

    public function addAction(){
        $storeData = $this->postData;
        $this->storeModel->insert($storeData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $storeID = $this->postData['storeID'];
        $set = $this->postData;
        $where = array(
            'storeID' => $storeID
        );
        $this->storeModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

}
