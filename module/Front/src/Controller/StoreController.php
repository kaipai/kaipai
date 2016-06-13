<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\BaseConst;
use Base\Functions\Utility;
use COM\Controller\Front;

class StoreController extends Front{

    public function detailAction(){
        $storeID = $this->queryData['storeID'];
        $auctionStatus = $this->queryData['auctionStatus'];
        $order = $this->queryData['order'];
        $sort = $this->queryData['sort'];
        $where = array(
            'storeID' => $storeID
        );
        $storeInfo = $this->storeModel->fetch($where);
        $storeCategories = $this->storeCategoryModel->select(array('storeID' => $storeID))->toArray();
        $storeRecommendProducts = $this->productModel->getProducts(array('isStoreRecommend' => 1, 'storeID' => $storeID));
        $storeRecommendProductsData = $storeRecommendProducts['data'];
        foreach($storeRecommendProductsData as $k => $v){
            $storeRecommendProductsData[$k]['leftTime'] = Utility::getLeftTime(time(), $v['endTime']);
        }


        if(!empty($order) && !empty($sort)) $order = $order .' '. $sort;
        $where = array('isStoreRecommend' => 0, 'storeID' => $storeID);
        if(!empty($auctionStatus)){
            $where['Product.auctionStatus'] = $auctionStatus;
        }
        $storeProducts = $this->productModel->getProducts($where, $this->pageNum, $this->limit, $order);
        $storeProductsData = $storeProducts['data'];
        foreach($storeProductsData as $k => $v){
            $storeProductsData[$k]['leftTime'] = Utility::getLeftTime(time(), $v['endTime']);
        }


        $this->view->setVariables(array(
            'storeInfo' => $storeInfo,
            'storeRecommendProducts' => $storeRecommendProductsData,
            'storeProducts' => $storeProductsData,
            'storeCategories' => $storeCategories,
            'pages' => $storeProducts['pages'],
            'auctionStatus' => $auctionStatus,
            'order' => $order,
            'sort' => $sort
        ));
        return $this->view;
    }
}