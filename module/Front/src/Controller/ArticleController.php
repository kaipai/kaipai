<?php
namespace Front\Controller;

use Base\ConstDir\BaseConst;
use COM\Controller\Front;

class ArticleController extends Front{

    public function listAction(){
        $articleType = $this->postData['type'];
        $where = array(
            'status' => BaseConst::ARTICLE_STATUS_ENABLE,
        );
        $where['type'] = $articleType;
        $articles = $this->articleModel->getList($where);;

        return $this->view;
    }

    public function detailAction(){
        $articleID = $this->postData['articleID'];
        $where = array(
            'articleID' => $articleID
        );
        $articleInfo = $this->articleModel->fetch($where);

        return $this->view;

    }
}