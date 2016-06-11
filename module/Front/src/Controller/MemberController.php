<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\Functions\Utility;
use COM\Controller\Front;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Where;

class MemberController extends Front{

    private $_storeInfo;

    public function init(){
        if(empty($this->memberInfo)) {
            if($this->request->isXmlHttpRequest){
                return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
            }else{
                return $this->redirect()->toUrl('/login/do-login');
            }

        }
        if(!empty($this->memberInfo['storeID'])){
            $this->_storeInfo = $this->storeModel->select(array('storeID' => $this->memberInfo['storeID']))->current();
            $this->layout()->setVariable('_storeInfo', $this->_storeInfo);
            $this->view->setVariables(array(
                '_storeInfo' => $this->_storeInfo
            ));
        }
        if((empty($this->_storeInfo) || $this->_storeInfo['verifyStatus'] != 2) && in_array($this->actionName, array('add-product', 'product', 'special', 'store-order', 'setting'))){
            return $this->redirect()->toUrl('/member/store-join');
        }
    }

    public function notificationAction(){
        return $this->view;
    }

    public function orderAction(){

        $search = $this->queryData['search'];
        $orderStatus = $this->queryData['orderStatus'];
        $where = new Where();
        $where->and->equalTo('MemberOrder.memberID', $this->memberInfo['memberID']);
        if(!empty($orderStatus)){
            $where->and->equalTo('MemberOrder.orderStatus', $orderStatus);
        }
        if(!empty($search)){
            $where->and->nest()->or->like('d.productName', '%' . $search . '%')->or->like('a.orderID', '%' . $search . '%')->or->like('e.storeName', '%' . $search . '%');
        }

        $result = $this->memberOrderModel->getOrderList($where, $this->pageNum, $this->limit);
        $orders = $result['data'];
        foreach($orders as $k => $v){
            if(!empty($v['productSnapshot'])){
                $tmp = json_decode($v['productSnapshot'], true);
                $orders[$k]['product'] = $tmp;
            }elseif(!empty($v['customizationSnapshot'])){
                $tmp = json_decode($v['customizationSnapshot'], true);
                $orders[$k]['product'] = array(
                    'productName' => $tmp['title'],
                    'currPrice' => $tmp['price'],
                    'depositPrice' => $tmp['depositPrice'],
                    'listImg' => $tmp['listImg'],
                );
            }
        }
        $this->view->setVariables(array(
            'orders' => $orders,
            'pages' => $result['pages'],
            'orderStatus' => $orderStatus,
            'search' => $search,
        ));
        return $this->view;
    }

    public function orderDetailAction(){
        $orderID = $this->queryData['orderID'];
        $where = array(
            'MemberOrder.orderID' => $orderID,
            'MemberOrder.memberID' => $this->memberInfo['memberID']
        );
        $orderInfo = $this->memberOrderModel->getOrderDetail($where);
        if(empty($orderInfo)){
            $this->view->setTemplate('error/404');
        }else{
            $this->view->setVariables(array(
                'info' => $orderInfo,
            ));
        }

        return $this->view;
    }

    public function auctionAction(){
        $auctions = $this->auctionMemberModel->getAuctionList($this->memberInfo['memberID']);

        $this->view->setVariables(array(

            'auctions' => $auctions
        ));
        return $this->view;
    }

    public function auctionListAction(){
        $auctions = $this->auctionMemberModel->getAuctionList($this->memberInfo['memberID']);

        $this->view->setVariables(array(

            'auctions' => $auctions
        ));
        return $this->view;
    }

    public function profileAction(){
        $where = array(
            'memberID' => $this->memberInfo['memberID'],
        );
        $memberInfo = $this->memberInfoModel->fetch($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $memberInfo);
    }

    public function interestProductAction(){
        $search = $this->queryData['search'];
        $auctionStatus = $this->queryData['auctionStatus'];
        $where = new Where();
        $where->and->equalTo('MemberProductInterest.memberID', $this->memberInfo['memberID']);
        if(!empty($auctionStatus)){
            $where->and->equalTo('b.auctionStatus', $auctionStatus);
        }
        if(!empty($search)){
            $where->and->nest()->or->like('b.productName', '%' . $search . '%');
        }

        $interestProducts = $this->memberProductInterestModel->getProducts($where, $this->pageNum, $this->limit);

        $this->view->setVariables(array(
            'products' => $interestProducts['data'],
            'pages' => $interestProducts['pages'],
            'auctionStatus' => $auctionStatus,
            'search' => $search,
        ));
        return $this->view;
    }

    public function interestStoreAction(){
        $search = $this->queryData['search'];
        $where = new Where();
        $where->and->equalTo('MemberStoreInterest.memberID', $this->memberInfo['memberID']);
        if(!empty($search)){
            $where->and->nest()->or->like('b.storeName', '%' . $search . '%');
        }

        $interestStores = $this->memberStoreInterestModel->getStores($where, $this->pageNum, $this->limit);

        $this->view->setVariables(array(
            'stores' => $interestStores['data'],
            'pages' => $interestStores['pages'],
            'search' => $search,
        ));
        return $this->view;
    }

    public function updateAction(){
        $where = array(
            'memberID' => $this->memberInfo['memberID']
        );
        $set = $this->postData;
        $purview = array('selfDesc', 'wechat', 'avatar', 'email', 'qq', 'signature');
        foreach($set as $k => $v) {
            if (!in_array($k, $purview)) unset($set[$k]);
        }
        if(empty($set)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        $this->memberInfoModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function logoutAction(){
        try{
            $this->tokenModel->delete(array('memberID' => $this->memberInfo['memberID']));
            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
        }catch(\Exception $e){
            return $this->response(ApiError::DATA_UPDATE_FAILED, ApiError::DATA_UPDATE_FAILED_MSG);
        }
    }

    public function addInterestProductAction(){
        $productID = $this->postData['productID'];
        try{
            $this->memberProductInterestModel->beginTransaction();
            $tmp = array(
                'productID' => $productID,
                'memberID' => $this->memberInfo['memberID'],
            );
            $this->memberProductInterestModel->insert($tmp);
            $this->productModel->update(array('interestedCount' => new Expression('interestedCount+1')), array('productID' => $productID));
            $this->memberProductInterestModel->commit();
            return $this->response(ApiSuccess::COMMON_SUCCESS, '关注成功');
        }catch (\Exception $e){
            $this->memberProductInterestModel->rollback();
            return $this->response($e->getCode(), $e->getMessage());
        }
    }

    public function removeInterestProductAction(){
        $productID = $this->postData['productID'];
        try{
            $this->memberProductInterestModel->beginTransaction();
            $tmp = array(
                'productID' => $productID,
                'memberID' => $this->memberInfo['memberID'],
            );
            $this->memberProductInterestModel->delete($tmp);
            $this->productModel->update(array('interestedCount' => new Expression('interestedCount-1')), array('productID' => $productID));
            $this->memberProductInterestModel->commit();
            return $this->response(ApiSuccess::COMMON_SUCCESS, '取消关注成功');
        }catch (\Exception $e){
            $this->memberProductInterestModel->rollback();
            return $this->response($e->getCode(), $e->getMessage());
        }
    }

    public function addInterestStoreAction(){
        $storeID = $this->postData['storeID'];
        try{
            $this->memberStoreInterestModel->beginTransaction();
            $tmp = array(
                'storeID' => $storeID,
                'memberID' => $this->memberInfo['memberID'],
            );
            $this->memberStoreInterestModel->insert($tmp);
            $this->storeModel->update(array('interestedCount' => new Expression('interestedCount+1')), array('storeID' => $storeID));
            $this->memberStoreInterestModel->commit();
            return $this->response(ApiSuccess::COMMON_SUCCESS, '关注成功');
        }catch (\Exception $e){
            $this->memberStoreInterestModel->rollback();
            return $this->response($e->getCode(), $e->getMessage());
        }
    }

    public function removeInterestStoreAction(){
        $storeID = $this->postData['storeID'];
        try{
            $this->memberStoreInterestModel->beginTransaction();
            $tmp = array(
                'storeID' => $storeID,
                'memberID' => $this->memberInfo['memberID'],
            );
            $this->memberStoreInterestModel->delete($tmp);
            $this->storeModel->update(array('interestedCount' => new Expression('interestedCount-1')), array('storeID' => $storeID));
            $this->memberStoreInterestModel->commit();
            return $this->response(ApiSuccess::COMMON_SUCCESS, '取消关注成功');
        }catch (\Exception $e){
            $this->memberStoreInterestModel->rollback();
            return $this->response($e->getCode(), $e->getMessage());
        }
    }

    public function noteAction(){
        return $this->view;
    }

    public function cancelOrderAction(){
        $orderID = $this->postData['orderID'];
        if(!empty($orderID)){
            $this->memberOrderModel->update(array('orderStatus' => -1), array('orderID' => $orderID, 'memberID' => $this->memberInfo['memberID'], 'orderStatus <= ?' => 1));
        }

        return $this->response(ApiSuccess::COMMON_SUCCESS, '取消成功');
    }





    public function storeCancelOrderAction(){
        $orderID = $this->postData['orderID'];
        if(!empty($orderID)){
            $this->memberOrderModel->update(array('orderStatus' => -1), array('orderID' => $orderID, 'storeID' => $this->memberInfo['storeID'], 'orderStatus <= ?' => 1));
        }

        return $this->response(ApiSuccess::COMMON_SUCCESS, '取消成功');
    }

    public function addProductAction(){
        $specialID = $this->queryData['specialID'];
        $productID = $this->queryData['productID'];

        $productInfo = $this->productModel->select(array('productID' => $productID, 'storeID' => $this->_storeInfo['storeID']))->current();
        if(!empty($productInfo)){
            $productCategoryFilterOptions = $this->productFilterOptionModel->select(array('productID' => $productID))->toArray();
            $productPropertyValues = $this->productPropertyValueModel->select(array('productID' => $productID))->toArray();
            $productInfo['detailImgs'] = json_decode($productInfo['detailImgs'], true);
            $deliveryCity = $this->regionModel->select(array('regionID' => $productInfo['deliveryCityID']))->current();
            $productInfo['deliveryProvinceID'] = $deliveryCity['pid'];
            $startTime = $productInfo['startTime'];
            $endTime = $productInfo['endTime'];
            $productInfo['startTimeYear'] = date('Y', $startTime);
            $productInfo['startTimeMonth'] = date('n', $startTime);
            $productInfo['startTimeDay'] = date('j', $startTime);
            $productInfo['startTimeHour'] = date('G', $startTime);
            $productInfo['startTimeMin'] = date('i', $startTime);
            $productInfo['endTimeYear'] = date('Y', $endTime);
            $productInfo['endTimeMonth'] = date('n', $endTime);
            $productInfo['endTimeDay'] = date('j', $endTime);
            $productInfo['endTimeHour'] = date('G', $endTime);
            $productInfo['endTimeMin'] = date('i', $endTime);
        }

        $productCategories = $this->productCategoryModel->select()->toArray();
        $storeCategory = $this->storeCategoryModel->select(array('storeID' => $this->_storeInfo['storeID']))->toArray();
        $specialWhere = array('storeID' => $this->_storeInfo['storeID']);
        if(!empty($specialID)){
            $specialWhere = array('specialID' => $specialID);
        }
        $specials = $this->specialModel->select($specialWhere)->toArray();

        $this->view->setVariables(array(
            'productCategories' => $productCategories,
            'storeCategory' => $storeCategory,
            'specials' => $specials,
            'specialID' => $specialID,
            'productInfo' => $productInfo,
            'productCategoryFilterOptions' => $productCategoryFilterOptions,
            'productPropertyValues' => $productPropertyValues,
        ));
        return $this->view;
    }

    public function addProductCategoryAction(){
        $this->view->setNoLayout();
        $productCategoryID = $this->queryData['productCategoryID'];
        $options = $this->productCategoryFilterOptionModel->getCategoryFilters(array('b.productCategoryID' => $productCategoryID));

        $this->view->setVariables(array(
            'options' => $options,
        ));
        return $this->view;
    }

    public function addSpecialAction(){
        if(empty($this->postData)){
            $specialID = $this->queryData['specialID'];
            $specialInfo = $this->specialModel->select(array('specialID' => $specialID, 'storeID' => $this->_storeInfo['storeID']))->current();
            if(!empty($specialInfo)){
                $startTime = $specialInfo['startTime'];
                $endTime = $specialInfo['endTime'];
                $specialInfo['startTimeYear'] = date('Y', $startTime);
                $specialInfo['startTimeMonth'] = date('n', $startTime);
                $specialInfo['startTimeDay'] = date('j', $startTime);
                $specialInfo['startTimeHour'] = date('G', $startTime);
                $specialInfo['startTimeMin'] = date('i', $startTime);
                $specialInfo['endTimeYear'] = date('Y', $endTime);
                $specialInfo['endTimeMonth'] = date('n', $endTime);
                $specialInfo['endTimeDay'] = date('j', $endTime);
                $specialInfo['endTimeHour'] = date('G', $endTime);
                $specialInfo['endTimeMin'] = date('i', $endTime);
            }
            $productCategories = $this->productCategoryModel->select()->toArray();
            $this->view->setVariables(array(
                'productCategories' => $productCategories,
                'specialInfo' => $specialInfo,

            ));
            return $this->view;
        }else{
            $this->postData['productCountLimit'] = intval($this->postData['productCountLimit']);
            if(!($this->postData['productCountLimit'] >= 20 && $this->postData['productCountLimit'] <= 60)){
                return $this->response(ApiError::COMMON_ERROR, '拍品数量限制在20-60个');
            }
            $this->postData['specialImg'] = Utility::saveBaseCodePic($this->postData['specialImg']);
            $this->postData['specialBanner'] = Utility::saveBaseCodePic($this->postData['specialBanner']);
            $this->postData['startTime'] = strtotime($this->postData['startTime']);
            $this->postData['endTime'] = strtotime($this->postData['endTime']);
            $this->postData['storeID'] = $this->_storeInfo['storeID'];
            if(!empty($this->postData['specialID'])){
                $where = array('specialID' => $this->postData['specialID'], 'storeID' => $this->_storeInfo['storeID']);
                $specialInfo = $this->specialModel->select($where)->current();
                if(empty($specialInfo)) return $this->response(ApiError::COMMON_ERROR, '专场不存在');
                unset($this->postData['specialID']);
                $this->specialModel->update($this->postData, $where);
                return $this->response(ApiSuccess::COMMON_SUCCESS, '更新成功');
            }else{
                $this->specialModel->insert($this->postData);
                return $this->response(ApiSuccess::COMMON_SUCCESS, '新增成功');
            }


        }

    }

    public function specialVerifyAction(){
        $specialID = $this->postData['specialID'];
        $this->specialModel->update(array('verifyStatus' => 1), array('specialID' => $specialID, 'storeID' => $this->_storeInfo['storeID']));

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function specialAction(){
        $specials = $this->specialModel->select(array('storeID' => $this->_storeInfo['storeID']))->toArray();

        $this->view->setVariables(array(
            'specials' => $specials,
        ));
        return $this->view;
    }

    public function specialProductAction(){
        $specialID = $this->queryData['specialID'];
        $special = $this->specialModel->select(array('specialID' => $specialID))->current();
        $products = $this->productModel->select()->toArray();
        $this->view->setVariables(array(
            'special' => $special,
            'products' => $products,
        ));
        return $this->view;
    }

    public function productAction(){
        $products = $this->productModel->getProducts(array('Product.storeID' => $this->memberInfo['storeID']), $this->pageNum, $this->limit);

        $this->view->setVariables(array(
            'products' => $products['data'],
            'pages' => $products['pages'],
        ));
        return $this->view;
    }

    public function storeSettingAction(){
        if(empty($this->postData)){
            $storeCategories = $this->storeCategoryModel->select(array('storeID' => $this->_storeInfo['storeID']))->toArray();
            $this->view->setVariables(array('storeCategories' => $storeCategories));
            return $this->view;
        }

        try{
            $this->storeModel->beginTransaction();
            $storeCategory = $this->postData['storeCategory'];
            unset($this->postData['storeCategory']);
            $storeID = $this->_storeInfo['storeID'];
            $where = array(
                'storeID' => $storeID
            );

            $this->postData['storeLogo'] = Utility::saveBaseCodePic($this->postData['storeLogo']);
            $this->postData['storeBanner'] = Utility::saveBaseCodePic($this->postData['storeBanner']);
            $this->postData['recommendImg'] = Utility::saveBaseCodePic($this->postData['recommendImg']);

            $this->storeModel->update($this->postData, $where);

            $this->storeCategoryModel->delete(array('storeID' => $storeID));
            foreach($storeCategory as $v){
                if(!empty($v)){
                    $tmp = array(
                        'storeID' => $storeID,
                        'storeCategoryName' => $v,
                    );
                    $this->storeCategoryModel->insert($tmp);
                }
            }
            $this->storeModel->commit();
            return $this->response(ApiSuccess::COMMON_SUCCESS, '保存成功');
        }catch (\Exception $e){
            $this->storeModel->rollback();
            return $this->response($e->getCode(), $e->getMessage());
        }

    }

    public function storeOrderAction(){
        $search = $this->queryData['search'];
        $orderStatus = $this->queryData['orderStatus'];
        $where = new Where();
        $where->and->equalTo('MemberOrder.storeID', $this->memberInfo['storeID']);
        if(!empty($orderStatus)){
            $where->and->equalTo('MemberOrder.orderStatus', $orderStatus);
        }
        if(!empty($search)){
            $where->and->nest()->or->like('d.productName', '%' . $search . '%')->or->like('a.orderID', '%' . $search . '%')->or->like('e.storeName', '%' . $search . '%');
        }

        $result = $this->memberOrderModel->getOrderList($where, $this->pageNum, $this->limit);
        $orders = $result['data'];
        foreach($orders as $k => $v){
            if(!empty($v['productSnapshot'])){
                $tmp = json_decode($v['productSnapshot'], true);
                $orders[$k]['product'] = $tmp;
            }elseif(!empty($v['customizationSnapshot'])){
                $tmp = json_decode($v['customizationSnapshot'], true);
                $orders[$k]['product'] = array(
                    'productName' => $tmp['title'],
                    'currPrice' => $tmp['price'],
                    'depositPrice' => $tmp['depositPrice'],
                    'listImg' => $tmp['listImg'],
                );
            }
        }
        $this->view->setVariables(array(
            'orders' => $orders,
            'pages' => $result['pages'],
            'orderStatus' => $orderStatus,
            'search' => $search,
        ));
        return $this->view;
    }

    public function categoryPropertyAction(){
        $categoryID = $this->queryData['productCategoryID'];
        $properties = $this->productCategoryPropertyModel->select(array('productCategoryID' => $categoryID))->toArray();
        $this->view->setVariables(array(
            'properties' => $properties
        ));

        return $this->view;
    }

    public function saveProductAction(){

        $data = $this->postData;
        $data['storeID'] = $this->_storeInfo['storeID'];
        $product = $data;
        unset($product['productCategoryFilters'], $product['productCategoryProperty'], $product['storeCategoryID']);
        if(!empty($data['detailImgs'])){
            $detailImgs = explode(',', trim($data['detailImgs'], ','));
            if(count($detailImgs) > 5) return $this->response(ApiError::COMMON_ERROR, '拍品图片不能超过5张');
            $product['listImg'] = current($detailImgs);
            $product['detailImgs'] = json_encode($detailImgs);
        }

        if(!empty($product['startTime'])){
            $product['startTime'] = strtotime($product['startTime']);
        }
        if(!empty($product['endTime'])){
            $product['endTime'] = strtotime($product['endTime']);
        }
        if(!empty($product['startPrice'])){
            $product['currPrice'] = $product['startPrice'];
        }

        if(!empty($data['productID'])){

            $where = array(
                'productID' => $product['productID'],
                'storeID' => $product['storeID']
            );
            $productInfo = $this->productModel->select($where)->current();
            if(empty($productInfo)) return $this->response(ApiError::COMMON_ERROR, '拍品不存在');
            if(!empty($productInfo['auctionStatus']) && $productInfo['auctionStatus'] != 1) return $this->response(ApiError::COMMON_ERROR, '该拍品不能被编辑');
        }


        try{
            $this->productModel->beginTransaction();

            if(!empty($productInfo)){
                unset($product['productID']);
                $this->productModel->update($product, $where);
                if(!empty($data['productCategoryFilters'])){
                    $this->productFilterOptionModel->delete(array('productID' => $where['productID']));
                }

                if(!empty($data['productCategoryProperty'])){
                    $this->productPropertyValueModel->delete(array('productID' => $where['productID']));
                }
                $productID = $where['productID'];
            }else{
                $this->productModel->insert($product);
                $productID = $this->productModel->getLastInsertValue();
            }

            if(!empty($data['productCategoryFilters'])){
                $productCategoryFilters = $data['productCategoryFilters'];
                foreach($productCategoryFilters as $v){
                    $tmp = array(
                        'productID' => $productID,
                        'productCategoryFilterOptionID' => $v,
                    );
                    $this->productFilterOptionModel->insert($tmp);
                }
            }

            if(!empty($data['productCategoryProperty'])){
                foreach($data['productCategoryProperty'] as $k => $v){
                    $tmp = array(
                        'productID' => $productID,
                        'productCategoryPropertyID' => $k,
                        'value' => $v,
                    );
                    $this->productPropertyValueModel->insert($tmp);
                }
            }

            $this->productModel->commit();
            if(!empty($productInfo)){
                return $this->response(ApiSuccess::COMMON_SUCCESS, '更新成功', array('productID' => $productID));
            }else{
                return $this->response(ApiSuccess::COMMON_SUCCESS, '新增成功', array('productID' => $productID));
            }

        }catch (\Exception $e){
            $this->productModel->rollback();
            return $this->response($e->getCode(), $e->getMessage());
        }

    }

    public function storeJoinAction(){
        if(empty($this->_storeInfo)){
            $this->view->setTemplate('/front/member/store-join-step1');
        }elseif($this->_storeInfo['verifyStatus'] == 1 || $this->_storeInfo['verifyStatus'] == 3){
            $this->view->setTemplate('/front/member/store-join-step2');
        }elseif($this->_storeInfo['verifyStatus'] == 2){
            $this->view->setTemplate('/front/member/store-join-step3');
        }


        return $this->view;
    }

    public function storeVerifyAction(){
        $store = $this->postData;
        $verifyCode = $this->mobileVerifyCodeModel->getLastVerifyCode($store['storeMobile']);
        if($verifyCode != $store['storeMobileCode']){
            return $this->response(ApiError::VERIFY_CODE_INVALID, ApiError::VERIFY_CODE_INVALID_MSG);
        }
        unset($store['storeMobileCode']);
        $store['idPic'] = Utility::saveBaseCodePic($store['idPic']);
        $store['memberIdPic'] = Utility::saveBaseCodePic($store['memberIdPic']);
        $store['memberID'] = $this->memberInfo['memberID'];


        try{
            $this->storeModel->beginTransaction();
            $this->storeModel->insert($store);
            $storeID = $this->storeModel->getLastInsertValue();
            $this->memberInfoModel->update(array('storeID' => $storeID), array('memberID' => $this->memberInfo['memberID']));
            $this->storeModel->commit();

            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
        }catch (\Exception $e){
            $this->storeModel->rollback();
            return $this->response($e->getCode(), $e->getMessage());
        }
    }

    public function delProductAction(){
        $productID = $this->postData['productID'];
        if(!empty($productID)){
            $this->productModel->update(array('isDel' => 1), array('productID' => $productID));
        }
        return $this->response(ApiSuccess::COMMON_SUCCESS, '删除成功');
    }

    public function recommendProductAction(){
        $productID = $this->postData['productID'];
        if(!empty($productID)){
            $this->productModel->update(array('isSpecialRecommend' => 1), array('productID' => $productID));
        }
        return $this->response(ApiSuccess::COMMON_SUCCESS, '推荐成功');
    }

    public function storeRecommendProductAction(){
        $productID = $this->postData['productID'];
        if(!empty($productID)){
            $this->productModel->update(array('isStoreRecommend' => $this->postData['isStoreRecommend']), array('productID' => $productID));
        }
        if($this->postData['isStoreRecommend'] == 1){
            return $this->response(ApiSuccess::COMMON_SUCCESS, '推荐成功');
        }else{
            return $this->response(ApiSuccess::COMMON_SUCCESS, '取消推荐成功');
        }

    }

    public function delSpecialAction(){
        $specialID = $this->postData['specialID'];
        if(!empty($specialID)){
            $this->specialModel->update(array('isDel' => 1), array('specialID' => $specialID));
        }
        return $this->response(ApiSuccess::COMMON_SUCCESS, '删除成功');
    }

    public function delImgAction(){
        $productInfo = $this->productModel->select(array('productID' => $this->postData['productID']))->current();
        if(!empty($productInfo)){
            $detailImgs = json_decode($productInfo['detailImgs'], true);
            foreach($detailImgs as $k => $v){
                if($v == $this->postData['path']){
                    unset($detailImgs[$k]);
                }
            }
            $detailImgs = json_encode(array_values($detailImgs));
            $this->productModel->update(array('detailImgs' => $detailImgs), array('productID' => $this->postData['productID']));
        }

        return $this->response(ApiSuccess::COMMON_SUCCESS, '删除成功');
    }

    public function publishProductAction(){
        $productID = $this->queryData['productID'];
        $where = array('productID' => $productID, 'storeID' => $this->_storeInfo['storeID']);
        $productInfo = $this->productModel->select($where)->current();
        if(!empty($productInfo)){
            $this->productModel->update(array('startTime' => time(), 'endTime' => strtotime('+1 day'), 'auctionStatus' => 2), $where);
        }
        return $this->redirect()->toUrl('/member/product');
    }


}