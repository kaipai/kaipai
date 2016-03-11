<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Api;

class AuctionController extends Api{

    public function attendAction(){
        $memberID = $this->memberInfo['memberID'];
        $productID = $this->postData['productID'];

        $data = array(
            'productID' => $productID,
            'memberID' => $memberID,
        );
        $this->auctionMemberModel->insert($data);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);

    }

    public function setProxyPriceAction(){
        $proxyPrice = $this->postData['proxyPrice'];
        $productID = $this->postData['productID'];

        if(empty($proxyPrice) || empty($productID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        $set = array(
            'proxyPrice' => $proxyPrice,
        );
        $where = array(
            'memberID' => $this->memberInfo['memberID'],
            'productID' => $this->postData['productID'],
        );
        $this->auctionMemberModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function biddingAction(){
        $productID  = $this->postData['productID'];
        if(empty($productID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);

        $productInfo = $this->productModel->select()->current();
        $currPrice = $productInfo['currPrice'] + $productInfo['auctionPerPrice'];
        $this->auctionMemberModel->update(array('currPrice' => $currPrice), array('productID' => $productID, 'memberID' => $this->memberInfo['memberID']));
        $logData = array(
            'productID' => $productID,
            'memberID' => $this->memberInfo['memberID'],
            'nickName' => $this->memberInfo['nickName'],
            'auctionPrice' => $currPrice,
        );
        $this->auctionLogModel->insert($logData);
        $this->productModel->update(array('currPrice' => $currPrice), array('productID' => $productID));

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('currPrice' => $currPrice));

    }

    public function logAction(){
        $productID = $this->postData['productID'];
        if(empty($productID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);

        $logs = $this->auctionLogModel->select(array('productID' => $productID))->toArray();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $logs);
    }

}