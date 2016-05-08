<?php
namespace Front\Controller;

use COM\Controller\Front;

class AuctionController extends Front{

    public function attendAction(){
        $memberID = $this->memberInfo['memberID'];
        $productID = $this->postData['productID'];

        $data = array(
            'productID' => $productID,
            'memberID' => $memberID,
        );
        $this->auctionMemberModel->insert($data);

        return $this->view;

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

        return $this->view;
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

        return $this->view;

    }

    public function logAction(){
        $productID = $this->postData['productID'];
        if(empty($productID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);

        $logs = $this->auctionLogModel->select(array('productID' => $productID))->toArray();

        return $this->view;
    }

}