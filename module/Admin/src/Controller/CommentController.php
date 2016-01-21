<?php
namespace Admin\Controller;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Admin;

class CommentController extends Admin{

    public function replyAction(){
        $commentID = $this->postData['commentID'];
        $replyContent = $this->postData['replyContent'];
        $this->commentModel->update(array('replyContent' => $replyContent), array('commentID' => $commentID));

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function delAction(){
        $commentID = $this->postData['commentID'];
        $this->commentModel->delete(array('commentID' => $commentID));
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }
}
