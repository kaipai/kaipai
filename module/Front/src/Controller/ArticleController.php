<?php
namespace Front\Controller;

use Base\ConstDir\BaseConst;
use Base\Functions\Utility;
use COM\Controller\Front;

class ArticleController extends Front{

    public function indexAction(){
        $articleCategoryID = $this->queryData['articleCategoryID'];
        $display = $this->queryData['display'] ? $this->queryData['display'] : 'preview';
        $articleCategories = $this->articleCategoryModel->getCategories();

        $where = array();
        if(!empty($articleCategoryID)) $where['b.articleCategoryID'] = $articleCategoryID;

        $articles = $this->articleModel->getArticles($this->pageNum, $this->limit, $where);

        foreach($articles['data'] as $k => $v){
            $articles['data'][$k]['articleContent'] = Utility::mbCutStr(Utility::getBodyText($v['articleContent']), 300);
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

    public function contentAction(){
        $this->view->setNoLayout();
        $this->detailAction();
        return $this->view;

    }

    public function settingAction(){
        $settingName = $this->queryData['settingName'];
        $settingInfo = $this->siteSettingModel->select(array('settingName' => $settingName))->current();
        $this->view->setVariables(array(
            'settingInfo' => $settingInfo
        ));
        return $this->view;
    }

    public function settingDetailAction(){
        $this->view->setNoLayout();
        $settingName = $this->queryData['settingName'];
        $this->view->setVariables(array('settingName' => $settingName));
        return $this->view;
    }
}