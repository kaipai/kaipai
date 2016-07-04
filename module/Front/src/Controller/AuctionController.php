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
        $productInfo = $this->productModel->select(array('productID' => $productID))->current();
        if(empty($proxyPrice) || empty($productID) || empty($productInfo)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        if($productInfo['storeID'] == $this->memberInfo['storeID']) return $this->response(ApiError::COMMON_ERROR, '不能对自己的拍品设置代理价');

        $auctionMember = $this->auctionMemberModel->existAuctionMember($productID, $this->memberInfo['memberID']);
        if(!empty($auctionMember['proxyPrice']) && $proxyPrice < $auctionMember['proxyPrice']) return $this->response(ApiError::COMMON_ERROR, '设置失败, 该价格低于之前设置的代理价');

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
        $auctionLog = $this->auctionLogModel->select(array('productID' => $productInfo['productID']))->current();
        if(empty($productInfo)) return $this->response(ApiError::COMMON_ERROR, '拍品不存在');
        if($productInfo['currPrice'] >= $auctionPrice && !empty($auctionLog)) return $this->response(ApiError::COMMON_ERROR, '出价应超过当前价格');
        if(empty($auctionLog) && $productInfo['currPrice'] > $auctionPrice) return $this->response(ApiError::COMMON_ERROR, '出价不能少于当前价格');
        if($productInfo['auctionStatus'] == 3) return $this->response(ApiError::COMMON_ERROR, '拍卖已结束');
        if($productInfo['auctionStatus'] != 2) return $this->response(ApiError::COMMON_ERROR, '拍卖尚未开始');
        if($productInfo['storeID'] == $this->memberInfo['storeID']) return $this->response(ApiError::COMMON_ERROR, '不能对自己发布的拍品竞拍');
        $auctionMember = $this->auctionMemberModel->select(array('productID' => $productID, 'memberID' => $this->memberInfo['memberID']))->current();
        if(!empty($auctionMember) && $productInfo['currPrice'] == $auctionMember['myCurrPrice']) return $this->response(ApiError::COMMON_ERROR, '当前您已经是最高出价');
        if((($auctionPrice - $productInfo['currPrice']) % $productInfo['auctionPerPrice']) !== 0) return $this->response(ApiError::COMMON_ERROR, '未按拍卖阶梯出价');
        try{
            $this->auctionModel->bidding($productID, $this->memberInfo['memberID'], $this->memberInfo['nickName'], $auctionPrice, $productInfo['auctionPerPrice']);
            return $this->response(ApiSuccess::COMMON_SUCCESS, '出价成功');

        }catch (\Exception $e){
            return $this->response($e->getCode(), $e->getMessage());
        }


    }


}