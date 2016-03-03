<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class SpecialController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $offset = $this->request->getQuery('offset', $this->offset);
        $limit = $this->request->getQuery('limit', $this->limit);
        $where = array();

        $data = array();

        $data['total'] = $this->specialModel->getCount($where);

        $data['rows'] = $this->specialModel->getList($where, "specialID desc", $offset, $limit);

        return $this->adminResponse($data);
    }

}
