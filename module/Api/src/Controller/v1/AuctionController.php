<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Api;

class AuctionController extends Api{

    public function attendAction(){
        $memberID = $this->postData['memberID'];
        $productID = $this->postData['productID'];

        $data = array(
            'productID' => $productID,
            'memberID' => $memberID,
        );
        $this->auctionMember->insert($data);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);

    }
}