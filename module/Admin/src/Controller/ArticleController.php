<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class ArticleController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $res = $this->articleModel->getArticles($this->pageNum, $this->limit);

        return $this->adminResponse(array('rows' => $res['data'], 'total' => $res['total']));
    }

    public function addAction(){
        $set = array(
            'articleName' => $this->postData['articleName'],
            'articleCategoryID' => $this->postData['articleCategoryID'],
            'articleContent' => $this->postData['articleContent'],
            'type' => $this->postData['type'],
        );
        $this->articleModel->insert($set);
        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
    }

    public function addViewAction(){
        $articleID = $this->request->getQuery('articleID');
        if(!empty($articleID)){
            $articleInfo = $this->articleModel->select(array('articleID' => $articleID))->current();
            $this->view->setVariable('articleInfo', $articleInfo);
        }

        $categories = $this->articleCategoryModel->select()->toArray();
        $this->view->setVariable('categories', $categories);

        return $this->view;
    }

    public function updateAction(){
        $articleID = $this->postData['articleID'];

        $where = array(
            'articleID' => $articleID
        );
        unset($this->postData['articleID']);
        $this->articleModel->update($this->postData, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
    }
}
