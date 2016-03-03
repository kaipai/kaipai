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
        $articleData = $this->postData;
        $this->articleModel->insert($articleData);

        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $articleID = $this->postData['articleID'];
        $set = $this->postData;
        $where = array(
            'articleID' => $articleID
        );
        $this->articleModel->update($set, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
    }

    public function categoryAction(){
        return $this->view;
    }

    public function categoryListAction(){
        $offset = $this->request->getQuery('offset', $this->offset);
        $limit = $this->request->getQuery('limit', $this->limit);
        $where = array();

        $data = array();

        $data['total'] = $this->articleCategoryModel->getCount($where);

        $data['rows'] = $this->articleCategoryModel->getList($where, "articleCategoryID desc", $offset, $limit);

        return $this->adminResponse($data);
    }



}
