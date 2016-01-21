<?php
namespace Admin\Controller;

use COM\Controller\Admin;

class ProductCategoryFilterController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $select = $this->productCategoryFilterModel->getSelect();
        $productCategoryFilters = $this->productCategoryFilterModel->selectWith($select)->toArray();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('productCategoryFilters' => $productCategoryFilters));
    }

    public function addAction(){
        $productCategoryFilterData = array(

        );
        $this->productCategoryFilterModel->insert($productCategoryFilterData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $productCategoryFilterID = $this->postData['productCategoryFilterID'];
        $set = array();
        $where = array(
            'productCategoryFilterID' => $productCategoryFilterID
        );
        $this->productCategoryFilterModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

}
