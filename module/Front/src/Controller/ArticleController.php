<?php
namespace Front\Controller;

use Base\ConstDir\BaseConst;
use COM\Controller\Front;

class ArticleController extends Front{

    public function listAction(){
        $articleCategoryID = $this->postData['articleCategoryID'];
        $where = array();
        if(!empty($articleCategoryID)) $where['b.articleCategoryID'] = $articleCategoryID;
        $articles = $this->articleModel->getArticles($this->pageNum, $this->limit, $where);

        $this->view->setVariables(array(
            'articles' => $articles
        ));
        return $this->view;
    }

    public function detailAction(){
        $articleID = $this->queryData['articleID'];
        $where = array(
            'articleID' => $articleID
        );
        $info = $this->articleModel->getInfo($where);
        $this->view->setVariables(array(
            'info' => $info
        ));
        return $this->view;

    }
}