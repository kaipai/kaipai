<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Front;

class MemberController extends Front{



    public function indexAction(){

        return $this->view;
    }

    public function addProductAction(){
        return $this->view;
    }

    public function addSpecialAction(){
        return $this->view;
    }

    public function specialAction(){
        return $this->view;
    }

    public function specialProductAction(){
        return $this->view;
    }

    public function productAction(){
        return $this->view;
    }

    public function settingAction(){
        return $this->view;
    }

    public function supplierOrderAction(){
        return $this->view;
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

    public function interestSupplierAction(){
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

}