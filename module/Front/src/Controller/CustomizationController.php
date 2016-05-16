<?php
namespace Front\Controller;
use COM\Controller\Front;

class CustomizationController extends Front{

    public function indexAction(){
        return $this->view;
    }

    public function listAction(){
        $customizations = $this->customizationModel->getList();

        return $this->view;
    }

    public function detailAction(){
        $customizationID = $this->queryData['customizationID'];


        $customizationInfo = $this->customizationModel->select(array('customizationID' => $customizationID))->current();

        return $this->view;
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

        return $this->view;
    }


}