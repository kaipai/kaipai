<?php
namespace Admin\Controller;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Admin;

class ArticleController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $select = $this->articleModel->getSelect();
        $articles = $this->articleModel->selectWith($select)->toArray();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('articles' => $articles));
    }

    public function addAction(){
        $articleData = array(
            'articleCategoryID' => $this->postData['articleCategoryID'],
            'articleName' => $this->postData['articleName'],
            'articleContent' => $this->postData['articleContent'],
        );
        $this->articleModel->insert($articleData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $articleID = $this->postData['articleID'];
        $set = array();
        $where = array(
            'articleID' => $articleID
        );
        $this->articleModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }



}
