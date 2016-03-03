<?php
namespace Admin\Controller;
use COM\Controller\Admin;

class FriendlyLinkController extends Admin{
    public function indexAction(){
        return $this->view;
    }

    public function listAction(){
        $offset = $this->request->getQuery('offset', $this->offset);
        $limit = $this->request->getQuery('limit', $this->limit);
        $where = array();

        $data = array();

        $data['total'] = $this->friendlyLinkModel->getCount($where);

        $data['rows'] = $this->friendlyLinkModel->getList($where, "linkID desc", $offset, $limit);

        return $this->adminResponse($data);
    }
}