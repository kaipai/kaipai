<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;
use Zend\Db\Sql\Where;

class ProductController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){

        $search = $this->queryData['search'];
        $sort = $this->queryData['sort'];
        $order = $this->queryData['order'];
        $where = new Where();
        if(!empty($search)){
            $where->or->like('Product.productName', '%' . $search . '%');
        }
        if(!empty($sort) && !empty($order)){
            $sortOrder = 'Product.' . $sort . ' ' . $order;
        }

        $res = $this->productModel->getProducts($where, $this->pageNum, $this->limit, $sortOrder);

        $data['total'] = $res['total'];

        $data['rows'] = $res['data'];

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
