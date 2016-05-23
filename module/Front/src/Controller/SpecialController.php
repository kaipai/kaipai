<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Front;

class SpecialController extends Front{

    public function indexAction(){
        $categoryThemeOptions = $this->productCategoryFilterOptionModel->getThemeOptions();
        $where = array(
            'startTime > ?' => time(),
        );
        $specials = $this->specialModel->getSpecials($where, $this->pageNum, $this->limit);

        $this->view->setVariables(array(
            'categoryThemeOptions' => $categoryThemeOptions,
            'specials' => $specials['data'],
            'pages' => $specials['pages'],
        ));
        return $this->view;
    }

    public function todayAction(){
        return $this->view;
    }

    public function themeAction(){
        return $this->view;
    }

    public function listAction(){
        $where = array();

        $select = $this->specialModel->getSelect();
        $select->where($where);
        $paginator = $this->specialModel->paginate($select);
        $paginator->setCurrentPageNumber(ceil($this->offset / $this->limit) + 1);
        $paginator->setItemCountPerPage($this->limit);
        $dataRows = $paginator->getCurrentItems()->getArrayCopy();
        $dataTotalCount = $paginator->getTotalItemCount();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('rows' => $dataRows, 'total' => $dataTotalCount));

    }

    public function detailAction(){
        $specialID = $this->postData['specialID'];
        $where = array(
            'specialID' => $specialID,
        );

        $specialInfo = $this->specialModel->fetch($where);

        return $this->view;
    }

    public function addAction(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
        unset($this->postData['token']);
        $this->postData['storeID'] = $this->memberInfo['storeID'];
        $this->specialModel->insert($this->postData);
        $specialID = $this->specialModel->getLastInsertValue();
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('specialID' => $specialID));
    }

    public function updateAction(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
        unset($this->postData['token']);
        $specialID = $this->postData['specialID'];
        if(empty($specialID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        $where = array(
            'specialID' => $specialID,
            'storeID' => $this->memberInfo['storeID'],
        );
        $this->specialModel->update($this->postData, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function delAction(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
        unset($this->postData['token']);
        $specialID = $this->postData['specialID'];
        if(empty($specialID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        $where = array(
            'specialID' => $specialID,
            'storeID' => $this->memberInfo['storeID'],
        );

        $this->specialModel->delete($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function productAction(){
        $specialID = $this->postData['specialID'];
        if(empty($specialID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        $where = array(
            'SpecialProduct.specialID' => $specialID
        );
        $select = $this->specialProductModel->getSelect();
        $select->columns(array());
        $select->join(array('b' => 'Product'), 'SpecialProduct.productID = b.productID');
        $select->where($where);

        $paginator = $this->specialProductModel->paginate($select);
        $paginator->setCurrentPageNumber(ceil($this->offset / $this->limit) + 1);
        $paginator->setItemCountPerPage($this->limit);
        $dataRows = $paginator->getCurrentItems()->getArrayCopy();
        $dataTotalCount = $paginator->getTotalItemCount();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('rows' => $dataRows, 'total' => $dataTotalCount));
    }
}