<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\Functions\Utility;
use COM\Controller\Api;
class MemberOrderController extends Api{

    public function init(){
        if(empty($this->memberInfo)) return $this->_return(ApiError::NEED_LOGININ, ApiError::NEED_LOGIN_MSG);
    }

    public function listAction(){
        $where = array(
            'memberID' => $this->memberInfo['memberID']
        );
        $memberOrders = $this->memberOrderModel->getList($where, $this->offset, $this->limit);


        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $memberOrders);
    }


}