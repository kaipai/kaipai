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
            if(!empty($v['url'])){
                preg_match_all('/memberArticleID=([\d]*)/', $v['url'], $matches);
                $memberArticleID = $matches[1][0];
                if(!empty($memberArticleID)){
                    $info = $this->memberArticleModel->setColumns(array('memberArticleContent'))->select(array('memberArticleID' => $memberArticleID))->current();
                    $v['articleContent'] = $info['memberArticleContent'];
                }
            }
            $articles['data'][$k]['imgs'] = Utility::getImgs($v['articleContent']);
            $body = Utility::getBodyText($v['articleContent']);
            if(mb_strlen($body) > 300){
                $articles['data'][$k]['fullContent'] = 0;
            }else{
                $articles['data'][$k]['fullContent'] = 1;
            }
            $articles['data'][$k]['articleContent'] = Utility::mbCutStr($body, 300);
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