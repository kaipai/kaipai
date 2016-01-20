<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Api;

class MemberMessageController extends Api{

    public function indexAction(){
        $comments = array();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);

    }

    public function readAction(){
        $msgID = $this->postData['msgID'];
        if(!empty($msgID)){
            $this->memberMsgModel->select()->current();

        }
    }
}