<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Api;

class ArtistController extends Api{

    public function indexAction(){
        $artists = $this->artistModel->select()->toArray();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('artists' => $artists));

    }

    public function detailAction(){
        $artistID = $this->postData['artistID'];
        $artistInfo = $this->artistModel->select(array('artistID' => $artistID))->current();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $artistInfo);

    }
}