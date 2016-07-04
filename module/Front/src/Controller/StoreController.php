<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\BaseConst;
use Base\Functions\Utility;
use COM\Controller\Front;
use Zend\Db\Sql\Predicate\IsNull;
use Zend\Db\Sql\Where;

class StoreController extends Front{
    public function indexAction(){
        $this->pageNum = !empty($this->pageNum) ? $this->pageNum : 1;
        $this->limit = !empty($this->limit) ? $this->limit : 15;
        $stores = $this->storeModel->getHotStores($this->pageNum, $this->limit);

        $this->view->setVariables(array(
            'stores' => $stores['data'],
            'pages' => $stores['pages']
        ));

        return $this->view;
    }

    public function detailAction(){
        $storeID = $this->queryData['storeID'];
        $auctionStatus = $this->queryData['auctionStatus'];
        $order = $this->queryData['order'];
        $sort = $this->queryData['sort'];
        $storeCategoryID = $this->queryData['storeCategoryID'];
        $where = array(
            'storeID' => $storeID
        );
        $storeInfo = $this->storeModel->fetch($where);
        $storeCategories = $this->storeCategoryModel->select(array('storeID' => $storeID))->toArray();
        /*$storeRecommendProducts = $this->productModel->getProducts(array('isStoreRecommend' => 1, 'storeID' => $storeID, 'auctionStatus' => array(1, 2)));
        $storeRecommendProductsData = $storeRecommendProducts['data'];
        foreach($storeRecommendProductsData as $k => $v){
            $storeRecommendProductsData[$k]['leftTime'] = Utility::getLeftTime(time(), $v['endTime']);
        }*/


        if(!empty($order) && !empty($sort)) $order = $order .' '. $sort;
        $where = new Where();
        $where->equalTo('isStoreRecommend', 0)->equalTo('storeID', $storeID);
        if(!empty($auctionStatus)){
            $where->equalTo('Product.auctionStatus', $auctionStatus);
        }else{
            $where->in('Product.auctionStatus', array(1, 2));
        }
        $where->isNull('Product.specialID');
        if(!empty($storeCategoryID)){
            $where->and->nest()->or->equalTo('firstStoreCategoryID', $storeCategoryID)->or->equalTo('secondStoreCategoryID', $storeCategoryID)->or->equalTo('thirdStoreCategoryID', $storeCategoryID);
        }

        $storeProducts = $this->productModel->getProducts($where, $this->pageNum, $this->limit, $order);
        $storeProductsData = $storeProducts['data'];
        foreach($storeProductsData as $k => $v){
            $storeProductsData[$k]['leftTime'] = Utility::getLeftTime(time(), $v['endTime']);
        }


        $this->view->setVariables(array(
            'storeInfo' => $storeInfo,
            //'storeRecommendProducts' => $storeRecommendProductsData,
            'storeProducts' => $storeProductsData,
            'storeCategories' => $storeCategories,
            'pages' => $storeProducts['pages'],
            'auctionStatus' => $auctionStatus,
            'order' => $this->queryData['order'],
            'sort' => $sort,
            'storeCategoryID' => $storeCategoryID,
        ));
        return $this->view;
    }

    public function descAction(){
        $storeID = $this->queryData['storeID'];
        $where = array(
            'storeID' => $storeID
        );
        $storeInfo = $this->storeModel->fetch($where);
        $this->view->setVariables(array(
            'storeInfo' => $storeInfo
        ));
        return $this->view;
    }
}