<?php
namespace Api\Controller\v1;
use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Api;

class NotificationController extends Api{
    public function init(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
    }

    public function indexAction(){
        $type = $this->request->getPost('type');
        $where = array(
            'memberID' => $this->memberInfo['memberID']
        );
        if(!empty($type)) $where['type'] = $type;
        $notifications = $this->notificationModel->getList($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $notifications);

    }
}