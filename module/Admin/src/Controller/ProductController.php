<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;
use Zend\Db\Sql\Expression;
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
        foreach($data['rows'] as $k => $v){
            if(!empty($v['recommendStartTime'])){
                $data['rows'][$k]['recommendStartTime'] = date('Y-m-d H:i:s', $v['recommendStartTime']);
            }
            if(!empty($v['recommendEndTime'])){
                $data['rows'][$k]['recommendEndTime'] = date('Y-m-d H:i:s', $v['recommendEndTime']);
            }

        }

        return $this->adminResponse($data);
    }

    public function updateAction(){
        $productID = $this->postData['productID'];

        $where = array(
            'productID' => $productID
        );

        unset($this->postData['productID']);
        if(isset($this->postData['recommendStartTime'])){
            if(!empty($this->postData['recommendStartTime'])) {
                $this->postData['recommendStartTime'] = strtotime($this->postData['recommendStartTime']);
            }else{
                $this->postData['recommendStartTime'] = new Expression('null');
            }
        }

        if(isset($this->postData['recommendEndTime'])){
            if(!empty($this->postData['recommendEndTime'])) {
                $this->postData['recommendEndTime'] = strtotime($this->postData['recommendEndTime']);
            }else{
                $this->postData['recommendEndTime'] = new Expression('null');
            }
        }

        $this->productModel->update($this->postData, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');
    }

    public function withdrawAction(){
        $productID = $this->postData['productID'];
        $this->productModel->withdraw($productID);
        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');
    }
}
