<?php
namespace Admin\Controller;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Admin;

class ProductController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $products = $this->producModel->getList();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $products);
    }

    public function addAction(){
        $productData = $this->postData;
        $this->productModel->insert($productData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $productID = $this->postData['productID'];
        $set = $this->postData;
        $where = array(
            'productID' => $productID
        );
        $this->productModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }
}
