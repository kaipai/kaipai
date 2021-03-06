<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;
use Zend\Db\Sql\Where;

class ArticleController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $search = $this->queryData['search'];
        $where = new Where();
        if(!empty($search)){
            $where->or->like('Article.articleName', '%' . $search .'%');
        }
        $res = $this->articleModel->getArticles($this->pageNum, $this->limit, $where);

        return $this->adminResponse(array('rows' => $res['data'], 'total' => $res['total']));
    }

    public function addAction(){
        $set = array(
            'articleName' => $this->postData['articleName'],
            'articleCategoryID' => $this->postData['articleCategoryID'],
            'articleContent' => $this->postData['articleContent'],
            'url' => $this->postData['url'],
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
