<?php
namespace Admin\Controller;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Admin;

class ProductCategoryController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $productCategories = $this->productCategoryModel->getList();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $productCategories);
    }

    public function addAction(){
        $productCategoryData = $this->postData;
        $this->productCategoryModel->insert($productCategoryData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $productCategoryID = $this->postData['productCategoryID'];
        $set = $this->postData;

        $where = array(
            'productCategoryID' => $productCategoryID
        );
        $this->productCategoryModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function delAction(){
        $productCategoryID = $this->postData['productCategoryID'];
        $where = array(
            'productCategoryID' => $productCategoryID
        );

        $this->productCategoryModel->delete($where);
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

}
