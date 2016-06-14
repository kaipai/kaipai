<?php
namespace Front\Controller;

use Base\ConstDir\BaseConst;
use Base\Functions\Utility;
use COM\Controller\Front;

class ArticleController extends Front{

    public function indexAction(){
        $articleCategoryID = $this->queryData['articleCategoryID'];
        $display = $this->queryData['display'] ? $this->queryData['display'] : 'list';
        $articleCategories = $this->articleCategoryModel->getCategories();

        $where = array();
        if(!empty($articleCategoryID)) $where['b.articleCategoryID'] = $articleCategoryID;

        $articles = $this->articleModel->getArticles($this->pageNum, $this->limit, $where);

        foreach($articles['data'] as $k => $v){
            $articles['data'][$k]['articleContent'] = Utility::mbCutStr(Utility::getBodyText($v['articleContent']), 100);
        }

        $this->view->setVariables(array(
            'articleCategories' => $articleCategories,
            'articles' => $articles['data'],
            'pages' => $articles['page'],
            'display' => $display,
            'articleCategoryID' => $articleCategoryID,
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