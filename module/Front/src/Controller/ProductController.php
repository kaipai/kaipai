<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\BaseConst;
use Base\Functions\Utility;
use COM\Controller\Front;
use Zend\Db\Sql\Predicate\Like;

class ProductController extends Front{
    public function indexAction(){
        $search = $this->queryData['search'];
        $productCategoryID = $this->queryData['productCategoryID'];
        $categoryInfo = $this->productCategoryModel->select(array('productCategoryID' => $productCategoryID))->current();
        $options = $this->productCategoryFilterOptionModel->getCategoryFilters(array('b.productCategoryID' => $productCategoryID));
        $stores = $this->storeModel->getHotStores(1, 20);

        $leftAds = $this->adModel->getAdByPosition(BaseConst::AD_POSITION_PRODUCT_LIST_LEFT);
        $rightAds = $this->adModel->getAdByPosition(BaseConst::AD_POSITION_PRODUCT_LIST_RIGHT);

        $this->view->setVariables(array(
            'categoryInfo' => $categoryInfo,
            'options' => $options,
            'stores' => $stores['data'],
            'leftAds' => $leftAds,
            'rightAds' => $rightAds,
            'search' => $search,
        ));
        return $this->view;
    }

    public function listAction(){
        $this->view->setNoLayout();
        $productCategoryID = $this->postData['productCategoryID'];
        $productCategoryFilterOptionID = $this->postData['productCategoryFilterOptionID'];
        $storeID = $this->postData['storeID'];
        $storeCategoryID = $this->postData['storeCategoryID'];
        $sort = $this->postData['sort'];
        $order = $this->postData['order'];
        $priceBegin = $this->postData['priceBegin'];
        $priceEnd = $this->postData['priceEnd'];
        $isAuctionAuth = $this->postData['isAuctionAuth'];
        $isCoverDelivery = $this->postData['isCoverDelivery'];
        $isRefundInDays = $this->postData['isRefundInDays'];
        $isCertificateCard = $this->postData['isCertificateCard'];
        $isAuthorAuth = $this->postData['isAuthorAuth'];
        $productStatus = $this->postData['status'];
        $search = $this->postData['search'];
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
        if(!empty($priceBegin)){
            $where['b.currPrice > ?'] = $priceBegin;
        }
        if(!empty($priceEnd)){
            $where['b.currPrice < ?'] = $priceEnd;
        }
        if(!empty($isAuctionAuth)){
            $where['b.auctionAuth'] = $isAuctionAuth;
        }
        if(!empty($isCoverDelivery)){
            $where['b.coverDelivery'] = $isCoverDelivery;
        }
        if(!empty($isRefundInDays)){
            $where['b.refundInDays'] = $isRefundInDays;
        }
        if(!empty($isCertificateCard)){
            $where['b.certificateCard'] = $isCertificateCard;
        }
        if(!empty($isAuthorAuth)){
            $where['b.authorAuth'] = $isAuthorAuth;
        }
        if(!empty($productStatus) && $productStatus != 'all'){
            if($productStatus == 'coming'){
                $where['b.auctionStatus'] = 1;
            }elseif($productStatus == 'processing'){
                $where['b.auctionStatus'] = 2;
            }

        }else{
            $where['b.auctionStatus'] = array(1, 2);
        }
        if(!empty($search)){
            $where[] = new Like('b.productName', '%' . $search . '%');
        }

        if($order == 'default') $order = 'b.productID desc';
        if($order == 'instime') $order = 'b.instime ' . $sort;
        if($order == 'price') $order = 'b.currPrice ' . $sort;
        if($order == 'interest') $order = 'b.interestedCount desc';


        $products = $this->productFilterOptionModel->getList($where, $order, $this->offset, $this->limit);

        $this->view->setVariables(array(
            'products' => $products['products'],
            'productsCount' => $products['productsCount'],
            'productsPage' => ceil($products['productsCount'] / $this->limit),
            'currPage' => $this->pageNum,
        ));
        return $this->view;
    }

    public function detailAction(){
        $productID = $this->queryData['productID'];
        $sourceSpecialID = $this->queryData['sourceSpecialID'];
        $sourceSpecialInfo = $this->specialModel->select(array('specialID' => $sourceSpecialID))->current();
        $where = array('productID' => $productID);
        $productInfo = $this->productModel->select($where)->current();

        // properties
        $select = $this->productPropertyValueModel->getSelect();
        $select->join(array('b' => 'ProductCategoryProperty'), 'ProductPropertyValue.productCategoryPropertyID = b.productCategoryPropertyID', array('propertyName'));
        $select->where(array('ProductPropertyValue.productID' => $productID));
        $properties = $this->productPropertyValueModel->selectWith($select)->toArray();
        $properties = array_chunk($properties, 2);
        // detail imgs
        if(!empty($productInfo['detailImgs'])){
            $detailImgs = json_decode($productInfo['detailImgs'], true);
        }

        // auction logs
        $auctionLogs = $this->auctionLogModel->select(array('productID' => $productID))->toArray();

        //recommend products
        if(!empty($sourceSpecialID)){
            $recommendProducts = $this->productModel->getSpecialRecommendProducts($sourceSpecialID);
        }else{
            $recommendProducts = $this->productModel->getStoreRecommendProducts($productInfo['storeID']);
        }
        if(!empty($this->memberInfo)){
            $proxyPrice = $this->auctionMemberModel->setColumns(array('proxyPrice'))->select(array('productID' => $productID, 'memberID' => $this->memberInfo['memberID']))->current();
            if(!empty($proxyPrice)) $proxyPrice = $proxyPrice['proxyPrice'];
        }

        // 关注信息
        $interestProduct = $this->memberProductInterestModel->select(array('productID' => $productID, 'memberID' => $this->memberInfo['memberID']))->current();
        $interestStore = $this->memberStoreInterestModel->select(array('storeID' => $productInfo['storeID'], 'memberID' => $this->memberInfo['memberID'],))->current();

        $this->view->setVariables(array(
            'productInfo' => $productInfo,
            'properties' => $properties,
            'detailImgs' => $detailImgs,
            'sourceSpecialInfo' => $sourceSpecialInfo,
            'auctionLogs' => $auctionLogs,
            'recommendProducts' => $recommendProducts,
            'proxyPrice' => $proxyPrice,
            'interestProduct' => $interestProduct,
            'interestStore' => $interestStore,
        ));

        return $this->view;
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