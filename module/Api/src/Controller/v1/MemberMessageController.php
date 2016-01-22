<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Api;

class MemberMessageController extends Api{

    public function indexAction(){
        $memberMessages = $this->memberMessageModel->getList();


        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('memberMessages' => $memberMessages));

    }

    public function readAction(){
        $messageID = $this->postData['messageID'];
        if(empty($messageID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        $this->memberMsgModel->update(array(), array('messageID' => $messageID));
    }
}