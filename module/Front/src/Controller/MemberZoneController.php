<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Front;

class MemberZoneController extends Front{


    public function indexAction(){
        return $this->view;
    }

    public function profileAction(){
        return $this->view;
    }

    public function articleListAction(){
        return $this->view;
    }

    public function articleDetailAction(){
        return $this->view;
    }

    public function postArticleAction(){
        return $this->view;
    }

    public function updateAction(){
        $where = array(
            'memberID' => $this->memberInfo['memberID']
        );
        $set = $this->postData;

        $this->memberZoneModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }
}