<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Api;

class CommentController extends Api{

    public function indexAction(){
        $select = $this->commentModel->getSelect();
        $paginator = $this->commentModel->paginate($select);
        $paginator->setCurrentPageNumber(ceil($this->offset / $this->limit) + 1);
        $paginator->setItemCountPerPage($this->limit);
        $comments = $paginator->getCurrentItems()->toArray();
        $commentsCount = $paginator->getTotalItemCount();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('comments' => $comments, 'commentsCount' => $commentsCount));

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