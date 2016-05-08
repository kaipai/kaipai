<?php
namespace Front\Controller;
use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Front;

class StoreCategoryController extends Front{

    public function init(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
    }

    public function addAction(){
        $storeCategoryName = $this->postData['storeCategoryName'];
        if(empty($storeCategoryName)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        $data = array(
            'storeID' => $this->memberInfo['storeID'],
            'storeCategoryName' => $storeCategoryName
        );
        $this->storeCategoryModel->insert($data);
        $storeCategoryID = $this->storeCategoryModel->getLastInsertValue();
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('storeCategoryID' => $storeCategoryID));
    }

    public function updateAction(){
        $storeCategoryName = $this->postData['storeCategoryName'];
        $storeCategoryID = $this->postData['storeCategoryID'];
        if(empty($storeCategoryName) || empty($storeCategoryID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        $data = array(
            'storeCategoryName' => $storeCategoryName
        );
        $where = array(
            'storeID' => $this->memberInfo['storeID'],
            'storeCategoryID' => $storeCategoryID
        );
        $this->storeCategoryModel->update($data, $where);
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function delAction(){
        $storeCategoryID = $this->postData['storeCategoryID'];
        if(empty($storeCategoryID) ) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        $where = array(
            'storeID' => $this->memberInfo['storeID'],
            'storeCategoryID' => $storeCategoryID
        );
        $this->storeCategoryModel->delete($where);
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }
}