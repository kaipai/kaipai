<?php
namespace Admin\Controller;
use COM\Controller\Admin;

class WebsiteController extends Admin{
    public function indexAction(){
        return $this->view;
    }

    public function smsAction(){


        return $this->view;
    }

    public function smsListAction(){
        $offset = $this->request->getQuery('offset', $this->offset);
        $limit = $this->request->getQuery('limit', $this->limit);
        $where = array();

        $data = array();

        $data['total'] = $this->smsLogModel->getCount($where);

        $data['rows'] = $this->smsLogModel->getList($where, "logID desc", $offset, $limit);

        return $this->adminResponse($data);
    }
}