<?php
namespace Admin\Controller;

use COM\Controller\Admin;

class StoreController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $select = $this->storeModel->getSelect();
        $stores = $this->storeModel->selectWith($select)->toArray();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('stores' => $stores));
    }

    public function addAction(){
        $storeData = array(

        );
        $this->storeModel->insert($storeData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $storeID = $this->postData['storeID'];
        $set = array();
        $where = array(
            'storeID' => $storeID
        );
        $this->storeModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

}
