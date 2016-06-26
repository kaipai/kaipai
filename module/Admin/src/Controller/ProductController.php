<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class ProductController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $offset = $this->request->getQuery('offset', $this->offset);
        $limit = $this->request->getQuery('limit', $this->limit);
        $where = array();

        $data = array();

        $data['total'] = $this->productModel->getCount($where);

        $data['rows'] = $this->productModel->getList($where, "productID desc", $offset, $limit);

        return $this->adminResponse($data);
    }

    public function updateAction(){
        $productID = $this->postData['productID'];

        $where = array(
            'productID' => $productID
        );

        unset($this->postData['productID']);
        $this->productModel->update($this->postData, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');
    }

    public function withdrawAction(){
        $productID = $this->postData['productID'];
        $this->productModel->withdraw($productID);
        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');
    }
}
