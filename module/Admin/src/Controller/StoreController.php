<?php
namespace Admin\Controller;

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

}
