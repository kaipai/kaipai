<?php
namespace Admin\Controller;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Admin;

class ArticleController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $select = $this->articleCategoryModel->getSelect();
        $articleCategories = $this->articleCategoryModel->selectWith($select)->toArray();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('articleCategories' => $articleCategories));
    }

    public function addAction(){
        $articleCategoryData = $this->postData;

        $this->articleCategoryModel->insert($articleCategoryData);
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $where = array(
            'articleCategoryID' => $this->postData['articleCategoryID']
        );
        $set = array();

        $this->articleCategoryModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function deleteAction(){
        $articleCategoryID = $this->postData['articleCategoryID'];
        $this->articleCategoryModel->delete(array('articleCategoryID' => $articleCategoryID));

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

}
