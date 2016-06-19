<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class ProductCategoryFilterOptionController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function addViewAction(){
        $productCategoryFilterOptionID = $this->queryData['productCategoryFilterOptionID'];
        $productFilters = $this->productCategoryFilterModel->getFilters(1, 100);

        if(!empty($productCategoryFilterOptionID)){
            $info = $this->productCategoryFilterOptionModel->select(array('productCategoryFilterOptionID' => $productCategoryFilterOptionID))->current();
        }
        $this->view->setVariables(array(
            'info' => $info,
            'productFilters' => $productFilters['data']
        ));
        return $this->view;
    }

    public function addAction(){

        if(!empty($this->postData['productCategoryFilterOptionID'])){
            $update = $this->postData;
            unset($update['productCategoryFilterOptionID']);
            $this->productCategoryFilterOptionModel->update($update, array('productCategoryFilterOptionID' => $this->postData['productCategoryFilterOptionID']));
        }else{
            unset($this->postData['productCategoryFilterOptionID']);
            $this->productCategoryFilterOptionModel->insert($this->postData);
        }

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');

    }

    public function listAction(){

        $res = $this->productCategoryFilterOptionModel->getOptions($this->pageNum, $this->limit);

        $this->adminResponse(array('rows' => $res['data'], 'total' => $res['total']));

        return $this->response;
    }

    public function delAction(){
        $productCategoryFilterOptionID = $this->postData['productCategoryFilterOptionID'];

        $this->productCategoryFilterOptionModel->delete(array('productCategoryFilterOptionID' => $productCategoryFilterOptionID));

        return $this->response(AdminSuccess::COMMON_SUCCESS, '删除成功');
    }

}
