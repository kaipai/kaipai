<?php
namespace Front\Controller;

use COM\Controller\Front;

class ArtistController extends Front{


    public function recommendAction(){
        $artistCategoryID = $this->queryData['artistCategoryID'];
        $artistCategories = $this->artistCategoryModel->select()->toArray();
        $where = array('Artist.type' => 1);
        if(!empty($artistCategoryID)){
            $where['Artist.artistCategoryID'] = $artistCategoryID;
        }
        $artists = $this->artistModel->getArtists($where, $this->pageNum, $this->limit);

        $this->view->setVariables(array(
            'artistCategories' => $artistCategories,
            'artistCategoryID' => $artistCategoryID,
            'artists' => $artists['data'],
        ));
        return $this->view;
    }

    public function detailAction(){
        $where = array(
            'artistID' => $this->queryData['artistID'],
        );
        $artistInfo = $this->artistModel->getArtistDetail($where);

        $this->view->setVariables(array(
            'info' => $artistInfo
        ));

        return $this->view;
    }

    public function artistDetailAction(){
        $this->view->setNoLayout();
        $this->detailAction();

        return $this->view;
    }

    public function artistAwardsAction(){
        $this->view->setNoLayout();
        $this->detailAction();

        return $this->view;
    }

    public function artistProductionsAction(){
        $this->view->setNoLayout();
        $this->detailAction();

        return $this->view;
    }
}