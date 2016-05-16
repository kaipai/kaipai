<?php
namespace Front\Controller;

use COM\Controller\Front;

class ArtistController extends Front{

    public function indexAction(){

        return $this->view;
    }

    public function recommendAction(){
        return $this->view;
    }

    public function listAction(){
        $artists = $this->artistModel->getList();

        return $this->view;

    }

    public function detailAction(){
        $where = array(
            'artistID' => $this->queryData['artistID'],
        );
        $artistInfo = $this->artistModel->fetch($where);

        return $this->view;
    }
}