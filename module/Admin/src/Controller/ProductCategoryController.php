<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class ProductCategoryController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $offset = $this->request->getQuery('offset', $this->offset);
        $limit = $this->request->getQuery('limit', $this->limit);
        $where = array();

        $data = array();

        $data['total'] = $this->productCategoryModel->getCount($where);

        $data['rows'] = $this->productCategoryModel->getList($where, "productCategoryID desc", $offset, $limit);

        return $this->adminResponse($data);
    }

    public function addAction(){
        $productCategoryData = $this->postData;
        $this->productCategoryModel->insert($productCategoryData);

        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $productCategoryID = $this->postData['productCategoryID'];
        $set = $this->postData;

        $where = array(
            'productCategoryID' => $productCategoryID
        );
        $this->productCategoryModel->update($set, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
    }

    public function delAction(){
        $productCategoryID = $this->postData['productCategoryID'];
        $where = array(
            'productCategoryID' => $productCategoryID
        );

        $this->productCategoryModel->delete($where);
        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
    }

}
