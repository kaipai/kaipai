<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\Functions\Utility;
use COM\Controller\Api;
class MemberOrderController extends Api{

    public function init(){
        if(empty($this->memberInfo)) return $this->_return(ApiError::NEED_LOGININ, ApiError::NEED_LOGIN_MSG);
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


}