<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class ArticleController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $articleCategories = $this->articleCategoryModel->getList();

        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG, $articleCategories);
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
