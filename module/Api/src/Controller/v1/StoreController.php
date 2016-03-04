<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\BaseConst;
use COM\Controller\Api;
class StoreController extends Api{


    public function listAction(){
        $where = array();
        if(!empty($this->postData['type'])) $where['type'] = $this->postData['type'];
        $select = $this->storeModel->getSelect();
        $select->where($where);
        $paginator = $this->storeModel->paginate($select);
        $paginator->setCurrentPageNumber(ceil($this->offset / $this->limit) + 1);
        $paginator->setItemCountPerPage($this->limit);
        $dataRows = $paginator->getCurrentItems();
        $dataTotalCount = $paginator->getTotalItemCount();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('rows' => $dataRows, 'total' => $dataTotalCount));
    }

    public function detailAction(){
        $where = array(
            'storeID' => $this->postData['storeID']
        );
        $storeInfo = $this->storeModel->fetch($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array($storeInfo));
    }

    public function addAction(){
        $this->storeModel->insert($this->postData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);

    }

    public function categoryAction(){
        $where = array(
            'storeID' => $this->postData['storeID']
        );
        $categories = $this->storeCategoryModel->getList($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $categories);
    }

    public function categoryAddAction(){
        $storeCategoryName = $this->request->getPost('storeCategoryName');
        if(empty($storeCategoryName)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        $data = array(
            'storeID' => $this->memberInfo['storeID'],
            'storeCategoryName' => $storeCategoryName
        );
        $this->storeCategoryModel->insert($data);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }


}