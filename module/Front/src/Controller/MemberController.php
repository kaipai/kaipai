<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\Functions\Utility;
use COM\Controller\Front;
use Zend\Db\Sql\Expression;

class MemberController extends Front{

    private $_storeInfo;

    public function init(){
        if(empty($this->memberInfo)) $this->redirect()->toUrl('/login/do-login');
        if(!empty($this->memberInfo['storeID'])){
            $this->_storeInfo = $this->storeModel->select(array('storeID' => $this->memberInfo['storeID']))->current();
            $this->layout()->setVariable('_storeInfo', $this->_storeInfo);
            $this->view->setVariables(array(
                '_storeInfo' => $this->_storeInfo
            ));
        }
        if((empty($this->_storeInfo) || $this->_storeInfo['verifyStatus'] != 2) && in_array($this->actionName, array('add-product', 'product', 'special', 'store-order', 'setting'))){
            $this->redirect()->toUrl('/member/store-join');
        }
    }

    public function notificationAction(){
        return $this->view;
    }

    public function orderAction(){

        return $this->view;
    }

    public function auctionAction(){
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
        $select = $this->memberProductInterestModel->getSelect();
        $select->join(array('b' => 'Product'), 'MemberProductInterest.productID = b.productID')
            ->where(array('MemberProductInterest.memberID' => $this->memberInfo['memberID']));

        $interestProducts = $this->memberProductInterestModel->selectWith($select)->toArray();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $interestProducts);
    }

    public function interestStoreAction(){
        $select = $this->memberStoreInterestModel->getSelect();
        $select->join(array('b' => 'Store'), 'MemberStoreInterest.storeID = b.storeID')
            ->where(array('MemberStoreInterest.memberID' => $this->memberInfo['memberID']));

        $interestStores = $this->memberStoreInterestModel->selectWith($select)->toArray();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $interestStores);
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
            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
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
            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
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
            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
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
            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
        }catch (\Exception $e){
            $this->memberStoreInterestModel->rollback();
            return $this->response($e->getCode(), $e->getMessage());
        }
    }







    public function addProductAction(){
        $productCategories = $this->productCategoryModel->select()->toArray();
        $storeCategory = $this->storeCategoryModel->select(array('storeID' => $this->_storeInfo['storeID']))->toArray();
        $specials = $this->specialModel->select(array('storeID' => $this->_storeInfo['storeID']))->toArray();

        $this->view->setVariables(array(
            'productCategories' => $productCategories,
            'storeCategory' => $storeCategory,
            'specials' => $specials,
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
            $productCategories = $this->productCategoryModel->select()->toArray();
            $this->view->setVariables(array(
                'productCategories' => $productCategories,

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
            $this->specialModel->insert($this->postData);

            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
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
        $products = array();
        $this->view->setVariables(array(
            'special' => $special
        ));
        return $this->view;
    }

    public function productAction(){
        $products = $this->productModel->getProducts(array('Product.storeID' => $this->memberInfo['storeID']), $this->pageNum, $this->limit);

        $this->view->setVariables(array(
            'products' => $products
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
        $detailImgs = explode(',', trim($data['detailImgs'], ','));
        $product['listImg'] = current($detailImgs);
        $product['detailImgs'] = json_encode($detailImgs);
        $product['startTime'] = strtotime($product['startTime']);
        $product['endTime'] = strtotime($product['endTime']);
        $product['currPrice'] = $product['startPrice'];
        try{
            $this->productModel->beginTransaction();



            $this->productModel->insert($product);
            $productID = $this->productModel->getLastInsertValue();
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

            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('productID' => $productID));
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

}