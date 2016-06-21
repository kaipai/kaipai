<?php
namespace Front\Controller;

use Base\ConstDir\Api\Sms;
use Base\ConstDir\BaseConst;
use Base\Functions\Utility;
use COM\Controller;
use Zend\Db\Sql\Predicate\Between;
use Zend\Db\Sql\Predicate\IsNotNull;
use Zend\Db\Sql\Predicate\IsNull;

class CrontabController extends Controller{

    public function auctionAction(){
        $expireProducts = $this->productModel->getExpireProducts();

        try{
            $this->productModel->beginTransaction();
            foreach($expireProducts as $v){
                if(!empty($v['keepPrice']) && $v['keepPrice'] > $v['currPrice']){
                    $this->productModel->update(array('auctionStatus' => 3), array('productID' => $v['productID']));
                    $this->auctionMemberModel->update(array('status' => 2), array('productID' => $v['productID']));
                }else{
                    if(empty($v['auctionMemberID'])){
                        $this->productModel->update(array('auctionStatus' => 3), array('productID' => $v['productID']));
                    }else{
                        $this->productModel->update(array('auctionStatus' => 3), array('productID' => $v['productID']));
                        $this->auctionMemberModel->update(array('status' => 2), array('productID' => $v['productID']));
                        $this->auctionMemberModel->update(array('status' => 1), array('auctionMemberID' => $v['auctionMemberID']));

                        $businessID = $this->memberOrderModel->genBusinessID();
                        $unitePayID = $this->memberOrderModel->genUnitePayID();
                        $orderData = array(
                            'businessID' => $businessID,
                            'unitePayID' => $unitePayID,
                            'memberID' => $v['memberID'],
                            'productID' => $v['productID'],
                            'orderType' => 1,
                            'storeID' => $v['storeID'],
                        );

                        $productInfo = $this->productModel->select(array('productID' => $v['productID']))->current();

                        $orderData['productSnapshot'] = json_encode($productInfo);
                        $commision = 0;
                        if(!empty($productInfo['commision'])){
                            $commision = $productInfo['currPrice'] * round(($productInfo['commision']/ 100), 2);
                        }
                        $payMoney = $productInfo['currPrice'] + $commision + $productInfo['deliveryPrice'];
                        $this->memberOrderModel->insert($orderData);
                        $payData = array(
                            'unitePayID' => $unitePayID,
                            'payMoney' => $payMoney,
                            'productPrice' => $productInfo['currPrice'],
                            'commision' => $commision,
                            'deliveryPrice' => $productInfo['deliveryPrice'],
                        );
                        $this->memberPayDetailModel->insert($payData);
                    }
                }
            }
            $this->productModel->commit();
        }catch (\Exception $e){

            $this->productModel->rollback();
        }

        return $this->response;
    }

    public function productsOnAction(){
        $this->productModel->update(array('auctionStatus' => 2), array('auctionStatus' => 1, 'startTime < ?' => time(), 'isPaid' => 1));

        return $this->response;
    }



    public function specialsOnAction(){
        $this->specialModel->update(array('auctionStatus' => 2), array('auctionStatus' => 1, 'startTime < ?' => time(), 'isPaid' => 1));

        return $this->response;
    }

    public function specialsOffAction(){
        $this->specialModel->update(array('auctionStatus' => 3), array('auctionStatus' => 2, 'endTime < ?' => time(), 'isPaid' => 1));

        return $this->response;
    }


}
