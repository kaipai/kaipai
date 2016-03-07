<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class ArticleController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $offset = $this->request->getQuery('offset', $this->offset);
        $limit = $this->request->getQuery('limit', $this->limit);
        $where = array();

        $data = array();

        $data['total'] = $this->articleModel->getCount($where);

        $data['rows'] = $this->articleModel->getList($where, "articleID desc", $offset, $limit);

        return $this->adminResponse($data);
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
        $set = array(
            'articleName' => $this->postData['articleName'],
            'articleCategoryID' => $this->postData['articleCategoryID'],
            'articleContent' => $this->postData['articleContent'],
            'type' => $this->postData['type'],
        );
        $where = array(
            'articleID' => $articleID
        );
        $this->articleModel->update($set, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
    }




    public function delAction(){
        $articleID = $this->request->getPost('articleID');
        if(!empty($articleID)){
            $this->articleModel->delete(array('articleID' => $articleID));
        }
        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
    }



}
