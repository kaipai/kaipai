<?php
namespace Admin\Controller;
use Base\ConstDir\Admin\AdminError;
use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class NotificationController extends Admin{
    public function indexAction(){

        return $this->view;
    }

    public function addAction(){
        if(empty($this->postData)) return $this->view;
        $type = $this->postData['type'];
        $content = $this->postData['content'];

        if($type == 1){
            $mobile = $this->postData['mobile'];
            $memberInfo = $this->memberInfoModel->setColumns(array('memberID'))->select(array('mobile' => $mobile))->current();
            $this->notificationModel->insert(array('type' => 1, 'memberID' => $memberInfo['memberID'], 'content' => $content));
        }elseif($type == 2){
            $this->notificationModel->insert(array('type' => 1, 'content' => $content));
        }

        return $this->response(AdminSuccess::COMMON_SUCCESS, '推送成功');

    }

    public function listAction(){

        $res = $this->notificationModel->getNotifications(array('Notification.type' => 1), $this->pageNum, $this->limit);

        $this->adminResponse(array('rows' => $res['data'], 'total' => $res['total']));

        return $this->response;
    }

}