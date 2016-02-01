<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Api;

class CommentController extends Api{

    public function listAction(){
        $select = $this->commentModel->getSelect();
        $paginator = $this->commentModel->paginate($select);
        $paginator->setCurrentPageNumber(ceil($this->offset / $this->limit) + 1);
        $paginator->setItemCountPerPage($this->limit);
        $comments = $paginator->getCurrentItems()->toArray();
        $commentsCount = $paginator->getTotalItemCount();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('comments' => $comments, 'commentsCount' => $commentsCount));

    }

    public function addAction(){
        $this->commentModel->insert($this->postData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }
}