<?php
namespace Admin\Controller;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Admin;

class ArtistController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $artists = $this->artistModel->getList();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $artists);
    }

    public function addAction(){
        $artistData = $this->postData;
        $this->artistModel->insert($artistData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $artistID = $this->postData['artistID'];
        $set = $this->postData;
        $where = array(
            'artistID' => $artistID
        );
        $this->articleModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }


}
