<?php
namespace Admin\Controller;

use COM\Controller\Admin;

class ProductCategoryController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $select = $this->productCategoryModel->getSelect();
        $productCategories = $this->productCategoryModel->selectWith($select)->toArray();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('productCategories' => $productCategories));
    }

    public function addAction(){
        $productCategoryData = array(

        );
        $this->productCategoryModel->insert($productCategoryData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $productCategoryID = $this->postData['productCategoryID'];
        $set = array();
        $where = array(
            'productCategoryID' => $productCategoryID
        );
        $this->productCategoryModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

}
