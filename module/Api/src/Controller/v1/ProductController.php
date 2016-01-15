<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiSuccess;
use Base\Functions\Utility;
use COM\Controller\Api;
class ProductController extends Api{

    public function listAction(){
        $select = $this->memberModel->getSelect();
        $paginator = $this->memberModel->paginate($select);
        $paginator->setCurrentPageNumber(ceil($this->offset / $this->limit) + 1);
        $paginator->setItemCountPerPage($this->limit);
        $dataRows = $paginator->getCurrentItems()->toArray();
        $dataTotalCount = $paginator->getTotalItemCount();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('rows' => $dataRows, 'total' => $dataTotalCount));
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





}