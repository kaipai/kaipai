<?php
namespace Admin\Controller;

use COM\Controller\Admin;

class ArtistController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $select = $this->artistModel->getSelect();
        $artists = $this->artistModel->selectWith($select)->toArray();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('artists' => $artists));
    }

    public function addAction(){
        $artistData = array(

        );
        $this->artistModel->insert($artistData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function updateAction(){
        $artistID = $this->postData['artistID'];
        $set = array();
        $where = array(
            'artistID' => $artistID
        );
        $this->articleModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }


}
