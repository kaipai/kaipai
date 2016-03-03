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
        $businessID = $this->memberOrderModel->genBusinessID();
        $unitePayID = $this->memberOrderModel->genUnitePayID();
        $orderData = array(
            'businessID' => $businessID,
            'unitePayID' => $unitePayID,
            'memberID' => $this->memberInfo['memberID'],
            'productID' => $this->postData['productID'],
        );
        $productInfo = $this->productModel->select(array('productID' => $this->postData['productID']))->current();
        $orderData['productSnapshot'] = json_encode($productInfo);
        $this->memberOrderModel->insert($orderData);
        $payData = array(
            'unitePayID' => $unitePayID,
            'payMoney' => $productInfo['currPrice'],
        );
        $this->memberPayDetailModel->insert($payData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function getDeliveryTypeAction(){
        $delivetyTypes = $this->deliveryTypeModel->getList();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $delivetyTypes);
    }

    public function setDeliveryAction(){
        $orderID = $this->request->getPost('orderID');
        $receiverName = $this->request->getPost('receiverName');
        $receiverMobile = $this->request->getPost('receiverMobile');
        $receiverAddr = $this->request->getPost('receiverAddr');

        if(empty($receiverName) || empty($orderID) || empty($receiverMobile) || empty($receiverAddr)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);

        $deliveryData = array(
            'orderID' => $orderID,
            'receiverName' => $receiverName,
            'receiverMobile' => $receiverMobile,
            'receiverAddr' => $receiverAddr,
        );
        $this->memberOrderModel->insert($deliveryData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }


}