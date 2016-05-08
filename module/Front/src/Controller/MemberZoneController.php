<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Front;

class MemberZoneController extends Front{

    public function init(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
    }

    public function updateAction(){
        $where = array(
            'memberID' => $this->memberInfo['memberID']
        );
        $set = $this->postData;

        $this->memberZoneModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }
}