<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Front;
use Zend\Db\Sql\Predicate\Expression;

class OrderController extends Front{

    public function init(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
    }

    public function generateAction(){
        $productID = $this->postData['productID'];
        $customizationID = $this->postData['customizationID'];
        $payType = $this->postData['payType'];
        $customizationCount = intval($this->postData['customizationCount']);
        if((empty($productID) && empty($customizationID)) || (!empty($customizationID) && empty($customizationCount))) return $this->response(ApiError::COMMON_ERROR, '参数错误');

        $businessID = $this->memberOrderModel->genBusinessID();
        $unitePayID = $this->memberOrderModel->genUnitePayID();
        $orderData = array(
            'businessID' => $businessID,
            'unitePayID' => $unitePayID,
            'memberID' => $this->memberInfo['memberID'],
            'productID' => $productID,
        );
        if(!empty($customizationID)){
            $orderData['customizationID'] = $customizationID;
            $orderData['customizationCount'] = $customizationCount;
            $customizationInfo = $this->customizationModel->select(array('customizationID' => $customizationID))->current();
            if(!empty($customizationInfo)){
                $orderData['customizationSnapshot'] = json_encode($customizationInfo);
            }else{
                return $this->response(ApiError::COMMON_ERROR, '定制信息不存在');
            }
            if($customizationInfo['lastNum'] < 1) return $this->response(ApiError::COMMON_ERROR, '定制数量已抢光');
            $payMoney = $customizationCount * $customizationInfo['price'];
        }elseif(!empty($productID)){
            $productInfo = $this->productModel->select(array('productID' => $productID))->current();
            $orderData['productSnapshot'] = json_encode($productInfo);
            $payMoney = $productInfo['currPrice'];
        }

        try{
            $this->memberOrderModel->beginTransaction();
            $this->memberOrderModel->insert($orderData);
            $payData = array(
                'unitePayID' => $unitePayID,
                'payMoney' => $payMoney,
                'payType' => $payType,
            );
            $this->memberPayDetailModel->insert($payData);
            if(!empty($customizationID)){
                $this->customizationModel->update(array('lastNum' => new Expression('lastNumber+1'), 'boughtCount' => new Expression('boughtCount+1')));
            }elseif(!empty($productID)){

            }

            $this->memberOrderModel->commit();

            if($payType == 1){

            }elseif($payType == 2){

            }elseif($payType == 3){


            }elseif($payType == 4){

            }

            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('unitePayID' => $unitePayID));
        }catch (\Exception $e){
            $this->memberOrderModel->rollback();

            return $this->response($e->getCode(), $e->getMessage());
        }



    }


    public function payAction(){
        $orderID = $this->postData['orderID'];
        $memberDeliveryID = $this->postData['memberDeliveryID'];
        $payType = $this->postData['payType'];
        $orderRemark = $this->postData['orderRemark'];
        $orderInfo = $this->memberOrderModel->select(array('orderID' => $orderID, 'memberID' => $this->memberInfo['memberID']))->current();
        if(empty($orderInfo)) return $this->response(ApiError::COMMON_ERROR, '订单信息不存在');
        try{
            $this->memberOrderModel->beginTransaction();

            $this->memberOrderModel->update(array('remark' => $orderRemark), array('orderID' => $orderID));
            $this->memberPayDetailModel->update(array('payType' => $payType), array('unitePayID' => $orderInfo['unitePayID']));
            $orderDelivery = array(
                'orderID' => $orderID,
                'memberID' => $this->memberInfo['memberID'],
                'memberDeliveryID' => $memberDeliveryID,
            );
            $this->memberOrderDeliveryModel->insert($orderDelivery);

            $this->memberOrderModel->commit();
        }catch (\Exception $e){
            $this->memberOrderModel->rollback();

            return $this->response($e->getCode(), $e->getMessage());
        }
    }
}