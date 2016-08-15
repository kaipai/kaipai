<?php
namespace Front\Controller;

use COM\Controller\Front;

class CommentController extends Front{

    public function listAction(){
        $select = $this->commentModel->getSelect();
        $paginator = $this->commentModel->paginate($select);
        $paginator->setCurrentPageNumber($this->pageNum);
        $paginator->setItemCountPerPage($this->limit);
        $comments = $paginator->getCurrentItems()->getArrayCopy();
        $commentsCount = $paginator->getTotalItemCount();

        return $this->view;

    }

    public function addAction(){
        $data = array(
            'commentTitle' => $this->postData['commentTitle'],
            'commentContent' => $this->postData['commentContent'],
        );
        if(!empty($this->memberInfo)){
            $data['commenterName'] = $this->memberInfo['nickName'];
            $data['memberID'] = $this->memberInfo['memberID'];
        }
        $this->commentModel->insert($data);

        return $this->view;
    }
}