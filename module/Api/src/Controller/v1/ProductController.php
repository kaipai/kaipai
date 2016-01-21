<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiSuccess;
use Base\Functions\Utility;
use COM\Controller\Api;
class ProductController extends Api{

    public function listAction(){
        $searchItem = $this->postData['searchItem'];
        $select = $this->productModel->getSelect();
        $paginator = $this->productModel->paginate($select);
        $paginator->setCurrentPageNumber(ceil($this->offset / $this->limit) + 1);
        $paginator->setItemCountPerPage($this->limit);
        $products = $paginator->getCurrentItems()->toArray();
        $productsCount = $paginator->getTotalItemCount();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('products' => $products, 'productsCount' => $productsCount));
    }

    public function detailAction(){
        $productID = $this->postData['productID'];
        $select = $this->productModel->getSelect();
        $select->from(array('a' => 'Product'))
            ->join(array('b' => 'ProductCategory'), 'a.productCategoryID = b.productCategoryID', 'categoryName');
        $select->where(array('productID' => $productID));
        $productDetail = $this->productModel->selectWith($select)->current();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $productDetail);
    }

    public function categoryAction(){
        $select = $this->productCategoryFilterOptionModel->getSelect();
        $select->from(array('a' => 'ProductCategoryFilterOption'))
            ->join(array('b' => 'ProductCategoryFilter', 'a.productCategoryFilterID = b.productCategoryFilterID'))
            ->join(array('c' => 'ProductCategory', 'b.productCategoryID = c.productCategoryID'));
        $categories = $this->productCategoryFilterOptionModel->selectWith($select)->toArray();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('categories' => $categories));
    }

    public function addAction(){

    }

    public function updateAction(){

    }

    public function deleteAction(){

    }



}