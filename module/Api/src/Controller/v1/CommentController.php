<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Api;

class CommentController extends Api{

    public function listAction(){
        $select = $this->commentModel->getSelect();
        $paginator = $this->commentModel->paginate($select);
        $paginator->setCurrentPageNumber($this->pageNum);
        $paginator->setItemCountPerPage($this->limit);
        $comments = $paginator->getCurrentItems();
        $commentsCount = $paginator->getTotalItemCount();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('comments' => $comments, 'commentsCount' => $commentsCount));

    }

    public function addAction(){
        $data = array(
            'commentTitle' => $this->postData['commentTitle'],
            'commentContent' => $this->postData['commentContent'],
        );
        $this->commentModel->insert($data);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }
}