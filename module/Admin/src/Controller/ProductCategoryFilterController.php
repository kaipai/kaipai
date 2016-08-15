<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class ProductCategoryFilterController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function addViewAction(){
        $productCategoryFilterID = $this->queryData['productCategoryFilterID'];
        $productCategories = $this->productCategoryModel->select()->toArray();
        if(!empty($productCategoryFilterID)){
            $info = $this->productCategoryFilterModel->select(array('productCategoryFilterID' => $productCategoryFilterID))->current();
        }
        $this->view->setVariables(array(
            'info' => $info,
            'productCategories' => $productCategories
        ));
        return $this->view;
    }

    public function addAction(){

        if(!empty($this->postData['productCategoryFilterID'])){
            $update = $this->postData;
            unset($update['productCategoryFilterID']);
            $this->productCategoryFilterModel->update($update, array('productCategoryFilterID' => $this->postData['productCategoryFilterID']));
        }else{
            unset($this->postData['productCategoryFilterID']);
            $this->productCategoryFilterModel->insert($this->postData);
        }

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');

    }

    public function listAction(){

        $res = $this->productCategoryFilterModel->getFilters($this->pageNum, $this->limit);

        $this->adminResponse(array('rows' => $res['data'], 'total' => $res['total']));

        return $this->response;
    }

    public function delAction(){
        $productCategoryFilterID = $this->postData['productCategoryFilterID'];

        $this->productCategoryFilterModel->delete(array('productCategoryFilterID' => $productCategoryFilterID));

        return $this->response(AdminSuccess::COMMON_SUCCESS, '删除成功');
    }

}
