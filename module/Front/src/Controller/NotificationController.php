<?php
namespace Front\Controller;
use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Front;

class NotificationController extends Front{
    public function init(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
    }

    public function indexAction(){
        $type = $this->request->getPost('type');
        $where = array(
            'memberID' => $this->memberInfo['memberID']
        );
        if(!empty($type)) $where['type'] = $type;
        $this->notificationModel->update(array('read' => 1), $where);
        $notifications = $this->notificationModel->getList($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $notifications);

    }
}