<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\Functions\Utility;
use COM\Controller\Front;

class ProductController extends Front{

    public function listAction(){
        $productCategoryID = $this->postData['productCategoryID'];
        $productCategoryFilterOptionID = $this->postData['productCategoryFilterOptionID'];
        $storeID = $this->postData['storeID'];
        $storeCategoryID = $this->postData['storeCategoryID'];
        $where = array();
        if(!empty($productCategoryID)){
            $where['b.productCategoryID'] = $productCategoryID;
        }
        if(!empty($productCategoryFilterOptionID)){
            $where['ProductFilterOption.productCategoryFilterOptionID'] = $productCategoryFilterOptionID;
        }
        if(!empty($storeID)){
            $where['b.storeID'] = $storeID;
        }
        if(!empty($storeCategoryID)){
            $where['b.storeCategoryID'] = $storeCategoryID;
        }

        $products = $this->productFilterOptionModel->getList($where, null, $this->offset, $this->limit);

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
        $categoryFilters = $this->productCategoryFilterModel->getList();
        $categoryFilterOptions = $this->productCategoryFilterOptionModel->getList();

        $categories = Utility::recreateIndex($categories, 'productCategoryID');
        $categoryFilters = Utility::recreateIndex($categoryFilters, 'productCategoryFilterID');
        $categoryFilterOptions = Utility::recreateIndex($categoryFilterOptions, 'productCategoryFilterOptionID');
        foreach($categoryFilterOptions as $k => $v){
            $categoryFilters[$v['productCategoryFilterID']]['options'][$k] = $v;
        }
        foreach($categoryFilters as $k => $v){
            $categories[$v['productCategoryID']]['filters'][$k] = $v;
        }

        $result = array();
        foreach($categories as $k => $v){
            $tmp = array();
            if(!empty($v['filters'])){
                foreach($v['filters'] as $sk => $sv){
                    $tmp2 = array();
                    if(!empty($sv['options'])){
                        foreach($sv['options'] as $ssk => $ssv){
                            $tmp2 = array(
                                'optionID' => $ssk,
                                'optionName' => $ssv['optionName'],
                            );
                        }
                    }
                    $tmp[] = array(
                        'filterID' => $sk,
                        'filterName' => $sv['filterName'],
                        'options' => $tmp2
                    );
                }
            }
            $result[] = array(
                'categoryID' => $k,
                'categoryName' => $v['categoryName'],
                'filters' => $tmp,
            );
        }

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $result);
    }

    public function categoryFilterAction(){
        $productCategoryID = $this->postData['productCategoryID'];
        $where = array();
        if(!empty($productCategoryID)){
            $where = array(
                'productCategoryID' => $productCategoryID
            );
        }

        $filters = $this->productCategoryFilterOptionModel->getCategoryFilters($where);
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $filters);
    }

    public function addAction(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
        unset($this->postData['token']);
        $this->postData['storeID'] = $this->memberInfo['storeID'];
        $this->productModel->insert($this->postData);
        $productID = $this->productModel->getLastInsertValue();
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('productID' => $productID));
    }

    public function updateAction(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
        unset($this->postData['token']);
        $productID = $this->postData['productID'];
        if(empty($productID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        $where = array(
            'productID' => $productID,
            'storeID' => $this->memberInfo['storeID'],
        );
        unset($this->postData['productID']);
        $this->productModel->update($this->postData, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function delAction(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
        unset($this->postData['token']);
        $productID = $this->postData['productID'];
        if(empty($productID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        $where = array(
            'productID' => $productID,
            'storeID' => $this->memberInfo['storeID'],
        );

        $this->productModel->delete($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }



}