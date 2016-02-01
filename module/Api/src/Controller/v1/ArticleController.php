<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\BaseConst;
use COM\Controller\Api;

class ArticleController extends Api{

    public function listAction(){
        $articleType = $this->postData['type'];
        $where = array(
            'status' => BaseConst::ARTICLE_STATUS_ENABLE,
        );
        $where['type'] = $articleType;
        $articles = $this->articleModel->getList($where);;

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $articles);

    }

    public function detailAction(){
        $articleID = $this->postData['articleID'];
        $where = array(
            'articleID' => $articleID
        );
        $articleInfo = $this->articleModel->fetch($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $articleInfo);

    }
}