<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class ProductCategoryFilterController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $productCategoryFilters = $this->productCategoryFilterModel->getList();

        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG, $productCategoryFilters);
    }

    public function addAction(){
        $productCategoryFilterData = $this->postData;
        $this->productCategoryFilterModel->insert($productCategoryFilterData);

        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $productCategoryFilterID = $this->postData['productCategoryFilterID'];
        $set = $this->postData;
        $where = array(
            'productCategoryFilterID' => $productCategoryFilterID
        );
        $this->productCategoryFilterModel->update($set, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
    }

}
