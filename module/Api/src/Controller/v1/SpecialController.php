<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Api;

class SpecialController extends Api{

    public function listAction(){
        $where = array();

        $select = $this->specialModel->getSelect();
        $select->where($where);
        $paginator = $this->specialModel->paginate($select);
        $paginator->setCurrentPageNumber(ceil($this->offset / $this->limit) + 1);
        $paginator->setItemCountPerPage($this->limit);
        $dataRows = $paginator->getCurrentItems();
        $dataTotalCount = $paginator->getTotalItemCount();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('rows' => $dataRows, 'total' => $dataTotalCount));

    }

    public function detailAction(){
        $where = array(
            'specialID' => $this->postData['specialID']
        );
        $specialInfo = $this->specialModel->fetch($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $specialInfo);
    }

    public function addAction(){
        $data = $this->postData;

        $this->specialModel->insert($data);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function delAction(){
        $where = array(
            'specialID' => $this->postData['specialID']
        );

        $this->specialModel->delete($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function productAction(){
        $specialID = $this->postData['specialID'];
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
        $dataRows = $paginator->getCurrentItems();
        $dataTotalCount = $paginator->getTotalItemCount();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('rows' => $dataRows, 'total' => $dataTotalCount));
    }
}