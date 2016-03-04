<?php
namespace Api\Controller\v1;
use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Api;

class CustomizationController extends Api{

    public function listAction(){
        $customizations = $this->customizationModel->getList();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $customizations);
    }

    public function detailAction(){
        $customizationID = $this->request->getPost('customizationID');
        if(empty($customizationID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);

        $customizationInfo = $this->customizationModel->select(array('customizationID' => $customizationID))->current();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $customizationInfo);
    }

    public function buyAction(){
        $customizationID = $this->request->getPost('customizationID');
        if(empty($customizationID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);

        $businessID = $this->memberOrderModel->genBusinessID();
        $unitePayID = $this->memberOrderModel->genUnitePayID();
        $orderData = array(
            'businessID' => $businessID,
            'unitePayID' => $unitePayID,
            'memberID' => $this->memberInfo['memberID'],
            'customizationID' => $customizationID,
        );
        $customizationInfo = $this->customizationModel->select(array('customizationID' => $customizationID))->current();
        $orderData['customizationSnapshot'] = json_encode($customizationInfo);
        $this->memberOrderModel->insert($orderData);
        $payData = array(
            'unitePayID' => $unitePayID,
            'payMoney' => $customizationInfo['price'],
        );
        $this->memberPayDetailModel->insert($payData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('unitePayID' => $unitePayID));
    }


}