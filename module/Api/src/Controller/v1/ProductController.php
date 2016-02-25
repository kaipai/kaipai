<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\BaseConst;
use Base\Functions\Utility;
use COM\Controller\Api;
class ProductController extends Api{

    public function listAction(){
        $productCategoryID = $this->postData['productCategoryID'];
        $productCategoryFilterOptionID = $this->postData['productCategoryFilterOptionID'];
        $where = array();
        if(!empty($productCategoryID)){
            $where['b.productCategoryID'] = $productCategoryID;
        }
        if(!empty($productCategoryFilterOptionID)){
            $where['productFilterOption.productCategoryFilterOptionID'] = $productCategoryFilterOptionID;
        }

        $products = $this->productModel->getList($where, null, $this->offset, $this->limit);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $products);
    }

    public function detailAction(){
        $productID = $this->postData['productID'];
        $select = $this->productModel->getSelect();
        $select->join(array('b' => 'ProductCategory'), 'Product.productCategoryID = b.productCategoryID', 'categoryName');
        $select->where(array('productID' => $productID));
        $productDetail = $this->productModel->selectWith($select)->current();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $productDetail);
    }

    public function categoryAction(){

        $categories = $this->productCategoryModel->getList();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $categories);
    }

    public function categoryFilterAction(){
        $productCategoryID = $this->postData['productCategoryID'];
        $where = array();
        if(!empty($productCategoryID)){
            $where = array(
                'productCategoryID' => $productCategoryID
            );
        }

        $filters = $this->productCategoryFilterModel->getList($where);
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $filters);
    }

    public function addAction(){
        $this->productModel->insert($this->postData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $where = array();
        $this->productModel->update($this->postData, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function delAction(){
        $where = array(
            'productID' => $this->postData['productID']
        );

        $this->productModel->delete($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }



}