<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\Sms;
use Base\ConstDir\BaseConst;
use Base\Functions\Utility;
use COM\Controller;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Between;
use Zend\Db\Sql\Predicate\IsNotNull;
use Zend\Db\Sql\Predicate\IsNull;

class CrontabController extends Controller{

    public function auctionAction(){
        $expireProducts = $this->productModel->getExpireProducts();

        try{
            $this->productModel->beginTransaction();
            foreach($expireProducts as $v){
                /*if(!empty($v['keepPrice']) && $v['keepPrice'] > $v['currPrice']){
                    $this->productModel->update(array('auctionStatus' => 0, 'startTime' => new Expression('null'), 'endTime' => new Expression('null'), 'soldStatus' => 1, 'unSoldTime' => time()), array('productID' => $v['productID']));

                    $auctionMembers = $this->auctionMemberModel->select(array('productID' => $v['productID']))->toArray();
                    foreach($auctionMembers as $sv){
                        $this->notificationModel->insert(array('type' => 3, 'memberID' => $sv['memberID'], 'content' => '您未成功竞得<<' . $v['productName'] . '>>。'));
                    }

                    //$this->auctionMemberModel->update(array('status' => 2), array('productID' => $v['productID']));
                    $this->auctionMemberModel->delete(array('productID' => $v['productID']));
                    $this->auctionLogModel->delete(array('productID' => $v['productID']));
                    $this->memberProductInterestModel->delete(array('productID' => $v['productID']));
                    $this->productModel->update(array('currPrice' => new Expression('startPrice')), array('productID' => $v['productID']));
                }else{*/
                    if(empty($v['auctionMemberID'])){
                        if(!empty($v['specialID'])){
                            $this->productModel->update(array('auctionStatus' => 3, 'soldStatus' => 1, 'unSoldTime' => time()), array('productID' => $v['productID']));
                        }else{
                            $this->productModel->update(array('auctionStatus' => 0, 'isPaid' => 0, 'startTime' => new Expression('null'), 'endTime' => new Expression('null'), 'soldStatus' => 1, 'unSoldTime' => time()), array('productID' => $v['productID']));
                        }
                        $this->memberProductInterestModel->delete(array('productID' => $v['productID']));

                    }else{
                        $this->productModel->update(array('auctionStatus' => 3, 'soldStatus' => 2, 'soldTime' => time()), array('productID' => $v['productID']));

                        $auctionMembers = $this->auctionMemberModel->select(array('productID' => $v['productID'], 'auctionMemberID != ?' => $v['auctionMemberID']))->toArray();
                        foreach($auctionMembers as $sv){
                            $this->notificationModel->insert(array('type' => 3, 'memberID' => $sv['memberID'], 'content' => '您未成功竞得<<' . $v['productName'] . '>>。'));
                        }

                        $content = '您已成功竞得<<' . $v['productName'] . '>>, 请于72小时内付款, 超时交易将自动关闭。';
                        $this->notificationModel->insert(array('type' => 3, 'memberID' => $v['memberID'], 'content' => $content));
                        $memberInfo = $this->memberInfoModel->select(array('memberID' => $v['memberID']))->current();
                        $auctionProductCount = $memberInfo['auctionProductCount'] + 1;
                        $auctionProductNotPaidPercent = round(($auctionProductCount - $memberInfo['auctionProductPaidCount']) / $auctionProductCount, 2);
                        $this->memberInfoModel->update(array('auctionProductCount' => $auctionProductCount, 'auctionProductNotPaidPercent' => $auctionProductNotPaidPercent), array('memberID' => $v['memberID']));


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

                        $properties = $this->productPropertyValueModel->getProperties($v['productID']);

                        $productInfo['properties'] = $properties;

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
                //}
            }
            $this->productModel->commit();
        }catch (\Exception $e){

            $this->productModel->rollback();
        }

        return $this->response;
    }

    public function productsOnAction(){
        $this->productModel->update(array('auctionStatus' => 2), array('auctionStatus' => array(0, 1), 'startTime < ?' => time(), 'endTime > ?' => time(), 'isPaid' => 1, new IsNull('specialID')));

        return $this->response;
    }



    public function specialsOnAction(){
        $specials = $this->specialModel->setColumns(array('specialID', 'startTime', 'endTime'))->select(array('auctionStatus' => 1, 'startTime < ?' => time(), 'isPaid' => 1))->toArray();
        $this->specialModel->update(array('auctionStatus' => 2), array('auctionStatus' => 1, 'startTime < ?' => time(), 'isPaid' => 1));
        if(!empty($specials)){

            foreach($specials as $v){
                $this->productModel->update(array('auctionStatus' => 2, 'startTime' => $v['startTime'], 'endTime' => $v['endTime']), array('specialID' => $v['specialID']));
            }
        }

        return $this->response;
    }

    public function specialsOffAction(){
        $this->specialModel->update(array('auctionStatus' => 3), array('auctionStatus' => 2, 'endTime < ?' => time()));
        return $this->response;
    }

    public function interestProductStartAction(){
        $products = $this->productModel->setColumns(array('productID', 'productName'))->select(array('auctionStatus' => 1, 'startTime < ?' => time() + 900, 'isPaid' => 1))->toArray();
        try{
            $this->notificationModel->beginTransaction();
            foreach($products as $v){

                $data = $this->memberProductInterestModel->select(array('productID' => $v['productID']))->toArray();

                foreach($data as $sv){
                    $where = array('type' => 3, 'memberID' => $sv['memberID'], 'content' => '您关注的拍品<<' . $v['productName'] . '>>' . '马上就要开拍了。');
                    $exist = $this->notificationModel->select($where)->current();
                    if(!empty($exist)) continue;
                    $this->notificationModel->insert($where);
                }
            }
            $this->notificationModel->commit();

            return $this->response;
        }catch (\Exception $e){
            $this->notificationModel->rollback();

            return $this->response;
        }
    }

    public function interestProductEndAction(){
        $products = $this->productModel->select(array('auctionStatus' => 2, 'endTime < ?' => time() + 1200, 'isPaid' => 1))->toArray();

        try{
            $this->notificationModel->beginTransaction();
            foreach($products as $v){
                $data = $this->memberProductInterestModel->select(array('productID' => $v['productID']))->toArray();
                foreach($data as $sv){
                    $content = '您关注的拍品<<' . $v['productName'] . '>>' . '马上就要结束了。';
                    $where = array('type' => 3, 'memberID' => $sv['memberID'], 'content' => $content);
                    $exist = $this->notificationModel->select($where)->current();
                    if(!empty($exist)) continue;
                    $this->notificationModel->insert($where);

                    $memberInfo = $this->memberInfoModel->select(array('memberID' => $sv['memberID']))->current();
                    $this->smsService->sendSms($memberInfo['mobile'], $content);
                }
            }
            $this->notificationModel->commit();

            return $this->response;
        }catch (\Exception $e){
            $this->notificationModel->rollback();

            return $this->response;
        }
    }

    public function delUnsoldProductAction(){

        $this->productModel->update(array('isDel' => 1), array('soldStatus' => 1, 'auctionStatus' => 0, 'unSoldTime < ?' => strtotime('-3 days')));

        return $this->response;
    }

    public function delSoldProductAction(){

        $this->productModel->update(array('isDel' => 1), array('soldStatus' => 2, 'auctionStatus' => 3, 'soldTime < ?' => strtotime('-3 days')));

        return $this->response;
    }

    public function delOverTimeSpecialAction(){
        $where = array('auctionStatus' => 3, 'endTime < ?' => strtotime('-3 days'));
        $specials = $this->specialModel->setColumns(array('specialID'))->select($where)->toArray();
        try{
            $this->specialModel->beginTransaction();
            foreach($specials as $v){
                $this->productModel->update(array('isDel' => 1), array('specialID' => $v['specialID']));
                $this->specialModel->update(array('isDel' => 1), array('specialID' => $v['specialID']));
            }
            $this->specialModel->commit();
        }catch (\Exception $e){
            $this->specialModel->rollback();
        }



        return $this->response;
    }

    public function proxyPriceInvalidAction(){
        $proxyMembers = $this->auctionMemberModel->getAuctionList(array('AuctionMember.isNotified' => 0, 'AuctionMember.status' => 0, new IsNotNull('AuctionMember.proxyPrice')));
        foreach($proxyMembers as $v){
            if($v['proxyPrice'] < $v['currPrice']){
                $content = '您对拍品<<' . $v['productName'] . '>>设置的代理价已失效。';
                $where = array('type' => 3, 'memberID' => $v['memberID'], 'content' => $content);
                $this->notificationModel->insert($where);

                $memberInfo = $this->memberInfoModel->select(array('memberID' => $v['memberID']))->current();
                $this->smsService->sendSms($memberInfo['mobile'], $content);

                $this->auctionMemberModel->update(array('isNotified' => 1), array('auctionMemberID' => $v['auctionMemberID']));
            }
        }

        return $this->response;
    }

    public function cancelOrderAction(){
        $this->memberOrderModel->update(array('orderStatus' => -1), array('orderStatus' => 1, 'instime < ?' => date('Y-m-d H:i:s', strtotime('-3 days'))));

        return $this->response;
    }

    public function confirmDeliveryDoneAction(){
        $orders = $this->memberOrderModel->getOrders(array('orderStatus' => 4, 'autoConfirmDeliveryDoneTime < ?' => time(), 'returnStatus' => array(0, 2), 'isPaused' => 0, 'isComplainPaused' => 0));
        foreach($orders as $orderInfo){
            $this->memberOrderModel->confirmDeliveryDone($orderInfo);
        }

        return $this->response;
    }

    public function autoAcceptReturnApplyAction(){
        $orders = $this->memberOrderModel->select(array('returnStatus' => 1, 'returnType' => array(2, 3), 'applyReturnTime < ?' => strtotime('-4 days')))->toArray();
        foreach($orders as $v){
            if($v['returnType'] == 2){
                $orderInfo = $this->memberOrderModel->fetch(array('orderID' => $v['orderID']));
                if(!empty($orderInfo)){
                    $this->memberOrderModel->returnOrder($orderInfo);
                }
            }elseif($v['returnType'] == 3){
                $storeInfo = $this->storeModel->setColumns(array('returnReceiverName', 'returnReceiverMobile', 'returnReceiverAddr'))->select(array('storeID' => $v['storeID']))->current();
                $this->memberOrderModel->update(
                    array(
                        'returnStatus' => 3, 'orderReturnReceiverName' => $storeInfo['returnReceiverName'], 'orderReturnReceiverMobile' => $storeInfo['returnReceiverMobile'],
                        'orderReturnReceiverAddr' => $storeInfo['returnReceiverAddr'], 'acceptApplyReturnTime' => time(),
                    ),
                    array('orderID' => $v['orderID'])
                );
            }
        }

        return $this->response;
    }

    public function returnNoDeliveryAction(){
        $orders = $this->memberOrderModel->select(array('returnStatus' => 3, 'returnType' => array(3), 'acceptApplyReturnTime < ?' => strtotime('-3 days')))->toArray();
        foreach($orders as $v){
            $update = array('returnStatus' => 6, 'isPaused' => 0);
            if(!empty($v['autoConfirmDeliveryDoneTime']) && !empty($v['applyReturnTime'])){
                $update['autoConfirmDeliveryDoneTime'] = $v['autoConfirmDeliveryDoneTime'] + (time() - $v['applyReturnTime']);
            }
            $this->memberOrderModel->update($update, array('orderID' => $v['orderID']));
        }

        return $this->response;
    }

    public function confirmReturnDeliveryDoneAction(){
        $orders = $this->memberOrderModel->select(array('returnStatus' => 4, 'returnType' => array(3), 'returnDeliveryTime < ?' => strtotime('-10 days')))->toArray();
        foreach($orders as $v){
            $orderInfo = $this->memberOrderModel->fetch(array('orderID' => $v['orderID']));
            if(!empty($orderInfo)){
                $this->memberOrderModel->returnOrder($orderInfo);
            }
        }

        return $this->response;
    }

    public function removeInterestProductsAction(){
        $products = $this->memberProductInterestModel->getProducts(array('b.auctionStatus' => 3, 'b.endTime < ?' => strtotime('-3 days')), 1, 10);
        $products = $products['data'];
        foreach($products as $v){
            $this->memberProductInterestModel->delete(array('productInterestID' => $v['productInterestID']));
        }

        return $this->response;
    }



}
