<?php
namespace Api\Model;

use Base\ConstDir\Api\ApiError;
use COM\Model;
use Zend\Db\Sql\Expression;

class Auction extends Model{

    public function bidding($productID, $memberID, $nickName, $auctionPrice, $auctionPerPrice = 0){
        $productInfo = $this->productModel->setColumns(array('productName', 'delayType', 'specialID'))->select(array('productID' => $productID))->current();
        $proxyMember = $this->auctionMemberModel->getDetail(array('AuctionMember.productID' => $productID, 'AuctionMember.memberID != ?' => $memberID, 'AuctionMember.proxyPrice > ?' => $auctionPrice));
        try{
            $this->productModel->beginTransaction();

            $topBid = $this->auctionMemberModel->getTopBid($productID);
            if(!empty($topBid)){
                $this->notificationModel->insert(array('type' => 3, 'memberID' => $topBid['memberID'], 'content' => '您的<<' . $productInfo['productName'] . '>>拍品出价被超越。'));
            }
            $productUpdate = array(
                'currPrice' =>  $auctionPrice,
                'auctionCount' => new Expression('auctionCount + 1')
            );
            if($productInfo['delayType'] == 1){
                $newEndTime = time() + 300;
                $productUpdate['endTime'] = $newEndTime;
                if(!empty($productInfo['specialID'])){
                    $this->specialModel->update(array('endTime' => $newEndTime), array('specialID' => $productInfo['specialID']));
                }
            }
            $this->productModel->update($productUpdate, array('productID' => $productID));
            $this->auctionLogModel->insert(array('productID' => $productID, 'memberID' => $memberID, 'nickName' => $nickName, 'auctionPrice' => $auctionPrice));
            $this->auctionMemberModel->existAuctionMember($productID, $memberID);
            $this->auctionMemberModel->update(array('myCurrPrice' => $auctionPrice/*, 'proxyPrice' => new Expression('null')*/), array('productID' => $productID, 'memberID' => $memberID));



            $this->productModel->commit();
            if(!empty($proxyMember)){
                $auctionPrice += $auctionPerPrice;
                $this->bidding($productID, $proxyMember['memberID'], $proxyMember['nickName'], $auctionPrice, $auctionPerPrice);
            }
            return true;
        }catch (\Exception $e){
            $this->productModel->rollback();

            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

}