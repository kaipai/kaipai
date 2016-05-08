<?php
namespace Front\Controller;

use COM\Controller\Front;

class ArtistController extends Front{

    public function listAction(){
        $artists = $this->artistModel->getList();

        return $this->view;

    }

    public function detailAction(){
        $where = array(
            'artistID' => $this->postData['artistID'],
        );
        $artistInfo = $this->artistModel->fetch($where);

        return $this->view;
    }
}