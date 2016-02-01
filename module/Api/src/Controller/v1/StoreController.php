<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\BaseConst;
use COM\Controller\Api;
class StoreController extends Api{


    public function listAction(){
        $where = array();
        $where['type'] = $this->postData['type'];
        $select = $this->storeModel->getSelect();
        $paginator = $this->storeModel->paginate($select);
        $paginator->setCurrentPageNumber(ceil($this->offset / $this->limit) + 1);
        $paginator->setItemCountPerPage($this->limit);
        $dataRows = $paginator->getCurrentItems()->toArray();
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
        $categories = $this->storeModel->getCategories($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $categories);
    }


}