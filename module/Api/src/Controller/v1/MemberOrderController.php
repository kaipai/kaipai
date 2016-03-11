<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\Functions\Utility;
use COM\Controller\Api;
class MemberOrderController extends Api{

    public function init(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
    }

    public function listAction(){
        $where = array(
            'MemberOrder.memberID' => $this->memberInfo['memberID']
        );
        $memberOrders = $this->memberOrderModel->getList($where, null, $this->offset, $this->limit);


        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $memberOrders);
    }

    public function addAction(){
        $productID = $this->postData['productID'];
        if(empty($productID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        $businessID = $this->memberOrderModel->genBusinessID();
        $unitePayID = $this->memberOrderModel->genUnitePayID();
        $orderData = array(
            'businessID' => $businessID,
            'unitePayID' => $unitePayID,
            'memberID' => $this->memberInfo['memberID'],
            'productID' => $productID,
        );
        $productInfo = $this->productModel->select(array('productID' => $productID))->current();
        $orderData['productSnapshot'] = json_encode($productInfo);
        $this->memberOrderModel->insert($orderData);
        $payData = array(
            'unitePayID' => $unitePayID,
            'payMoney' => $productInfo['currPrice'],
        );
        $this->memberPayDetailModel->insert($payData);

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
        $existDelivery = $this->memberOrderDelivery->select($where)->current();
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
        $existDelivery = $this->memberOrderDelivery->select($where)->current();
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