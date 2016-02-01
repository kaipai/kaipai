<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\Functions\Utility;
use COM\Controller\Api;
class MemberController extends Api{

    public function init(){
        if(empty($this->memberInfo)) return $this->_return(ApiError::NEED_LOGININ, ApiError::NEED_LOGIN_MSG);
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
        $select->from(array('a' => 'MemberProductInterest'))
            ->join(array('b' => 'Product'), 'a.productID = b.productID')
            ->where(array('a.memberID' => $this->memberInfo['memberID']));

        $interestProducts = $this->memberProductInterest->selectWith($select)->toArray();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $interestProducts);
    }

    public function interestStoreAction(){
        $select = $this->memberStoreInterestModel->getSelect();
        $select->from(array('a' => 'MemberStoreInterest'))
            ->join(array('b' => 'Store'), 'a.storeID = b.storeID')
            ->where(array('a.memberID' => $this->memberInfo['memberID']));

        $interestStores = $this->memberStoreInterest->selectWith($select)->toArray();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $interestStores);
    }

}