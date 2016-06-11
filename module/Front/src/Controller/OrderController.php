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

            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
        }catch (\Exception $e){
            $this->memberOrderModel->rollback();

            return $this->response($e->getCode(), $e->getMessage());
        }


        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('unitePayID' => $unitePayID));
    }











    public function getDeliveryTypeAction(){
        $delivetyTypes = $this->deliveryTypeModel->getList();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $delivetyTypes);
    }

    public function setDeliveryTypeAction(){
        $deliveryTypeID = $this->request->getPost('deliveryTypeID');
        $deliveryNum = $this->request->getPost('deliveryNum');
        $orderID = $this->request->getPost('orderID');
        if(empty($deliveryTypeID) || empty($deliveryNum) || empty($orderID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);

        $where = array(
            'memberID' => $this->memberInfo['memberID'],
            'orderID' => $orderID,
        );
        $existDelivery = $this->memberOrderDeliveryModel->select($where)->current();
        $set = array(
            'deliveryTypeID' => $deliveryTypeID,
            'deliveryNum' => $deliveryNum
        );
        if(!empty($existDelivery)){
            $this->memberOrderDeliveryModel->update($set, $where);
        }else{
            $data = array_merge($where, $set);
            $this->memberOrderDeliveryModel->insert($data);
        }

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function getMemberDeliveryAction(){
        $memberDelivery = $this->memberDeliveryModel->select(array('memberID' => $this->memberInfo['memberID']))->toArray();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $memberDelivery);
    }

    public function addMemberDeliveryAction(){
        $receiverName = $this->request->getPost('receiverName');
        $receiverMobile = $this->request->getPost('receiverMobile');
        $receiverAddr = $this->request->getPost('receiverAddr');
        if(empty($receiverName) || empty($receiverMobile) || empty($receiverAddr)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);

        $data = array(
            'memberID' => $this->memberInfo['memberID'],
            'receiverName' => $receiverName,
            'receiverMobile' => $receiverMobile,
            'receiverAddr' => $receiverAddr,
        );
        $this->memberDeliveryModel->insert($data);
        $memberDeliveryID = $this->memberDeliveryModel->getLastInsertValue();
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('memberDeliveryID' => $memberDeliveryID));
    }

    public function setMemberDeliveryAction(){
        $memberDeliveryID = $this->request->getPost('memberDeliveryID');
        $orderID = $this->request->getPost('orderID');
        if(empty($memberDeliveryID) || empty($orderID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);

        $where = array(
            'memberID' => $this->memberInfo['memberID'],
            'orderID' => $orderID,
        );
        $existDelivery = $this->memberOrderDeliveryModel->select($where)->current();
        $set = array(
            'memberDeliveryID' => $memberDeliveryID
        );
        if(!empty($existDelivery)){

            $this->memberOrderDeliveryModel->update($set, $where);
        }else{
            $data = array_merge($where, $set);
            $this->memberOrderDeliveryModel->insert($data);
        }


        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }


}