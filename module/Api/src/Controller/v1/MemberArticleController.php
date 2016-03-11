<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\Functions\Utility;
use COM\Controller\Api;
class MemberArticleController extends Api{

    public function init(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
    }

    public function listAction(){
        $where = array(
            'MemberArticle.memberID' => $this->memberInfo['memberID']
        );
        $memberArticles = $this->memberArticleModel->getList($where, null, $this->offset, $this->limit);


        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $memberArticles);
    }

    public function detailAction(){
        $memberArticleID = $this->postData['memberArticleID'];
        $where = array(
            'memberArticleID' => $memberArticleID,
            'memberID' => $this->memberInfo['memberID']
        );

        $memberArticle = $this->memberArticleModel->select($where)->current();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $memberArticle);
    }

    public function addAction(){
        $data = array(
            'memberArticleName' => $this->postData['memberArticleName'],
            'memberArticleContent' => $this->postData['memberArticleContent'],
            'memberID' => $this->memberInfo['memberID'],
        );
        $this->memberArticleModel->insert($data);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function delAction(){
        $where = array(
            'memberArticleID' => $this->postData['memberArticleID'],
            'memberID' => $this->memberInfo['memberID'],
        );
        $this->memberArticleModel->delete($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }
}