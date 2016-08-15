<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class ProductCategoryController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function addViewAction(){
        $productCategoryID = $this->queryData['productCategoryID'];
        if(!empty($productCategoryID)){
            $info = $this->productCategoryModel->select(array('productCategoryID' => $productCategoryID))->current();
        }
        $this->view->setVariables(array(
            'info' => $info
        ));
        return $this->view;
    }

    public function addAction(){

        if(!empty($this->postData['productCategoryID'])){
            $update = $this->postData;
            unset($update['productCategoryID']);
            $this->productCategoryModel->update($update, array('productCategoryID' => $this->postData['productCategoryID']));
        }else{
            unset($this->postData['productCategoryID']);
            $this->productCategoryModel->insert($this->postData);
        }

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');

    }

    public function listAction(){

        $res = $this->productCategoryModel->getCategories($this->pageNum, $this->limit);

        $this->adminResponse(array('rows' => $res['data'], 'total' => $res['total']));

        return $this->response;
    }

    public function delAction(){
        $productCategoryID = $this->postData['productCategoryID'];

        $this->productCategoryModel->delete(array('productCategoryID' => $productCategoryID));

        return $this->response(AdminSuccess::COMMON_SUCCESS, '删除成功');
    }


}
