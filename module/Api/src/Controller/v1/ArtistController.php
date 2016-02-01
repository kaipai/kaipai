<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Api;

class ArtistController extends Api{

    public function listAction(){
        $artists = $this->artistModel->getList();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $artists);

    }

    public function detailAction(){
        $where = array(
            'artistID' => $this->postData['artistID'],
        );
        $artistInfo = $this->artistModel->fetch($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $artistInfo);

    }
}