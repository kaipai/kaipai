<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Front;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\IsNull;

class AuctionController extends Front{

    public function init(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
    }

    public function attendAction(){
        $memberID = $this->memberInfo['memberID'];
        $productID = $this->postData['productID'];

        $data = array(
            'productID' => $productID,
            'memberID' => $memberID,
        );
        $this->auctionMemberModel->insert($data);

        return $this->response(ApiSuccess::COMMON_SUCCESS, '加入成功');
    }

    public function setProxyPriceAction(){
        $proxyPrice = $this->postData['proxyPrice'];
        $productID = $this->postData['productID'];

        if(empty($proxyPrice) || empty($productID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        $this->auctionMemberModel->existAuctionMember($productID, $this->memberInfo['memberID']);
        $set = array(
            'proxyPrice' => $proxyPrice,
        );
        $where = array(
            'memberID' => $this->memberInfo['memberID'],
            'productID' => $this->postData['productID'],
        );
        $this->auctionMemberModel->update($set, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, '设置成功');
    }

    public function biddingAction(){
        $productID  = $this->postData['productID'];
        $auctionPrice = $this->postData['auctionPrice'];
        if(empty($productID) || empty($auctionPrice)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);

        $productInfo = $this->productModel->select(array('productID' => $productID, 'isDel' => 0))->current();
        if(empty($productInfo)) return $this->response(ApiError::COMMON_ERROR, '拍品不存在');
        if($productInfo['currPrice'] >= $auctionPrice) return $this->response(ApiError::COMMON_ERROR, '出价应超过当前价格');
        if($productInfo['auctionStatus'] == 3) return $this->response(ApiError::COMMON_ERROR, '拍卖已结束');
        if($productInfo['auctionStatus'] != 2) return $this->response(ApiError::COMMON_ERROR, '拍卖尚未开始');
        if($productInfo['storeID'] == $this->memberInfo['storeID']) return $this->response(ApiError::COMMON_ERROR, '不能对自己发布的拍品竞拍');
        $auctionMember = $this->auctionMemberModel->select(array('productID' => $productID, 'memberID' => $this->memberInfo['memberID']))->current();
        if(!empty($auctionMember) && $productInfo['currPrice'] == $auctionMember['myCurrPrice']) return $this->response(ApiError::COMMON_ERROR, '当前您已经是最高出价');
        if($productInfo['auctionPerPrice'] != ($auctionPrice - $productInfo['currPrice'])) return $this->response(ApiError::COMMON_ERROR, '未按拍卖阶梯出价');
        try{
            $this->auctionModel->bidding($productID, $this->memberInfo['memberID'], $this->memberInfo['nickName'], $auctionPrice, $productInfo['auctionPerPrice']);
            return $this->response(ApiSuccess::COMMON_SUCCESS, '出价成功');

        }catch (\Exception $e){
            return $this->response($e->getCode(), $e->getMessage());
        }


    }


}