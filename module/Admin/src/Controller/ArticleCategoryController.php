<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class ArticleCategoryController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $offset = $this->request->getQuery('offset', $this->offset);
        $limit = $this->request->getQuery('limit', $this->limit);
        $where = array();

        $data = array();

        $data['total'] = $this->articleCategoryModel->getCount($where);

        $data['rows'] = $this->articleCategoryModel->getList($where, "articleCategoryID desc", $offset, $limit);

        return $this->adminResponse($data);

    }

    public function getAction(){
        $articleCategoryID = $this->postData['articleCategoryID'];
        $articleInfo = $this->articleCategoryModel->select(array('articleCategoryID' => $articleCategoryID))->current();

        return $this->adminResponse($articleInfo);
    }

    public function addAction(){
        $articleCategoryData = $this->postData;

        $this->articleCategoryModel->insert($articleCategoryData);
        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $where = array(
            'articleCategoryID' => $this->postData['articleCategoryID']
        );
        $set = $this->postData;

        $this->articleCategoryModel->update($set, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
    }

    public function delAction(){
        $articleCategoryID = $this->postData['articleCategoryID'];
        $this->articleCategoryModel->delete(array('articleCategoryID' => $articleCategoryID));

        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
    }


}
