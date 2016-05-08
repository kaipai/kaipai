<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\BaseConst;
use COM\Controller\Front;

class MemberMessageController extends Front{

    public function listAction(){
        $where = array(
            'memberID' => $this->memberInfo['memberID'],
        );
        $memberMessages = $this->memberMessageModel->getList($where, null, $this->offset, $this->limit);


        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $memberMessages);

    }

    public function readAction(){
        $messageID = $this->postData['messageID'];
        $this->memberMessageModel->update(array('status' => BaseConst::MEMBER_MESSAGE_STATUS_READ), array('messageID' => $messageID));
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }
}