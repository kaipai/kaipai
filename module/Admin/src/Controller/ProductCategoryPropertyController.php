<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class ProductCategoryPropertyController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function addViewAction(){
        $productCategoryPropertyID = $this->queryData['productCategoryPropertyID'];
        $productCategories = $this->productCategoryModel->select()->toArray();
        if(!empty($productCategoryPropertyID)){
            $info = $this->productCategoryPropertyModel->select(array('productCategoryPropertyID' => $productCategoryPropertyID))->current();
        }
        $this->view->setVariables(array(
            'info' => $info,
            'productCategories' => $productCategories
        ));
        return $this->view;
    }

    public function addAction(){

        if(!empty($this->postData['productCategoryPropertyID'])){
            $update = $this->postData;
            unset($update['productCategoryPropertyID']);
            $this->productCategoryPropertyModel->update($update, array('productCategoryPropertyID' => $this->postData['productCategoryPropertyID']));
        }else{
            unset($this->postData['productCategoryPropertyID']);
            $this->productCategoryPropertyModel->insert($this->postData);
        }

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');

    }

    public function listAction(){

        $res = $this->productCategoryPropertyModel->getProperties($this->pageNum, $this->limit);

        $this->adminResponse(array('rows' => $res['data'], 'total' => $res['total']));

        return $this->response;
    }

    public function delAction(){
        $productCategoryPropertyID = $this->postData['productCategoryPropertyID'];

        $this->productCategoryPropertyModel->delete(array('productCategoryPropertyID' => $productCategoryPropertyID));

        return $this->response(AdminSuccess::COMMON_SUCCESS, '删除成功');
    }

}
