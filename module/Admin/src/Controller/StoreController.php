<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminError;
use Base\ConstDir\Api\ApiError;
use COM\Controller\Admin;
use Base\ConstDir\BaseConst;
use Base\ConstDir\Admin\AdminSuccess;

class StoreController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $offset = $this->request->getQuery('offset', $this->offset);
        $limit = $this->request->getQuery('limit', $this->limit);
        $where = array();

        $data = array();

        $data['total'] = $this->storeModel->getCount($where);

        $data['rows'] = $this->storeModel->getList($where, "storeID desc", $offset, $limit);

        return $this->adminResponse($data);
    }

    public function verifyAction(){
        $verifyStatus = $this->request->getPost('verifyStatus');
        $storeID = $this->request->getPost('storeID');
        if(empty($status)) return $this->response(AdminError::PARAMETER_MISSING, AdminError::PARAMETER_MISSING_MSG);
        $set = array(
            'verifyStatus' => $verifyStatus,
        );
        $where = array(
            'storeID' => $storeID
        );
        $this->sm->get('Api\Model\Store')->update($set, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
    }

}
