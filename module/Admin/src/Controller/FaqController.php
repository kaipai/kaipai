<?php
namespace Admin\Controller;
use COM\Controller\Admin;

class FaqController extends Admin{
    public function indexAction(){
        return $this->view;
    }

    public function listAction(){
        $offset = $this->request->getQuery('offset', $this->offset);
        $limit = $this->request->getQuery('limit', $this->limit);
        $where = array();

        $data = array();

        $data['total'] = $this->faqModel->getCount($where);

        $data['rows'] = $this->faqModel->getList($where, "faqID desc", $offset, $limit);

        return $this->adminResponse($data);
    }
}