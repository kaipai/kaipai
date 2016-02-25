<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\Functions\Utility;
use COM\Controller\Api;
class MemberController extends Api{

    public function init(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
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

        $this->memberInfoModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

}