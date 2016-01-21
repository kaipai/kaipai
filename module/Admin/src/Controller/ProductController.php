<?php
namespace Admin\Controller;

use COM\Controller\Admin;

class ProductController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $select = $this->producModel->getSelect();
        $products = $this->producModel->selectWith($select)->toArray();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('products' => $products));
    }

    public function addAction(){
        $productData = array(

        );
        $this->productModel->insert($productData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $productID = $this->postData['productID'];
        $set = array();
        $where = array(
            'productID' => $productID
        );
        $this->productModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }
}
