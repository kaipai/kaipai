<?php
namespace Admin\Controller;
use COM\Controller\Admin;

class MemberArticleController extends Admin{
    public function indexAction(){
        return $this->view;
    }

    public function listAction(){
        $where = array();

        $data = array();
        $result = $this->memberArticleModel->getArticles($where, $this->pageNum, $this->limit);
        $data['total'] = $result['total'];

        $data['rows'] = $result['data'];

        return $this->adminResponse($data);
    }

}