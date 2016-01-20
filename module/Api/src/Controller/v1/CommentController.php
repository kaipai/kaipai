<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Api;

class CommentController extends Api{

    public function indexAction(){
        $comments = array();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);

    }

    public function addAction(){
        $data = array(
            'memberID' => $this->postData['memberID'],
            'commenterName' => $this->postData['memberName'],
            'commentTitle' => $this->postData['commentTitle'],
            'commentContent' => $this->postData['commentContent'],
        );
        $this->commentModel->insert($data);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }
}