<?php
namespace Front\Controller;
use Base\ConstDir\Api\ApiError;
use COM\Controller\Front;

class CustomizationController extends Front{

    public function indexAction(){
        $artistCategoryID = $this->queryData['artistCategoryID'];
        $artistCategories = $this->artistCategoryModel->select()->toArray();
        $where = array(
            'Customization.endTime > ?' => time(),
        );
        if(!empty($artistCategoryID)){
            $where['b.artistCategoryID'] = $artistCategoryID;
        }
        $customizations = $this->customizationModel->getAllCustomizations($where);

        $this->view->setVariables(array(
            'artistCategories' => $artistCategories,
            'artistCategoryID' => $artistCategoryID,
            'customizations' => $customizations,
        ));
        return $this->view;
    }

    public function listAction(){
        $customizations = $this->customizationModel->getCustomizations($this->pageNum, $this->limit);

        $this->view->setVariables(array(
            'customizations' => $customizations['data'],
            'pages' => $customizations['pages'],
        ));
        return $this->view;
    }

    public function detailAction(){
        $customizationID = $this->queryData['customizationID'];

        $customizationInfo = $this->customizationModel->fetch(array('customizationID' => $customizationID));
        $artistInfo = $this->artistModel->select(array('artistID' => $customizationInfo['artistID']))->current();
        $orders = $this->memberOrderModel->getOrders(array('customizationID' => $customizationInfo['customizationID'], 'orderStatus >= ?' => 2));
        $this->view->setVariables(array(
            'info' => $customizationInfo,
            'artistInfo' => $artistInfo,
            'orders' => $orders,
        ));
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