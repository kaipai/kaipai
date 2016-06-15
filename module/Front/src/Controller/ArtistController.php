<?php
namespace Front\Controller;

use COM\Controller\Front;

class ArtistController extends Front{


    public function recommendAction(){
        $artistCategoryID = !empty($this->queryData['artistCategoryID']) ? $this->queryData['artistCategoryID'] : 1;
        $artistCategories = $this->artistCategoryModel->select()->toArray();

        $artists = $this->artistModel->getArtists(array('Artist.artistCategoryID' => $artistCategoryID), $this->pageNum, $this->limit);

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
        $artistInfo = $this->artistModel->fetch($where);

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