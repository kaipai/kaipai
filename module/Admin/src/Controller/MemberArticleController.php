<?php
namespace Admin\Controller;
use COM\Controller\Admin;

class MemberArticleController extends Admin{
    public function indexAction(){
        return $this->view;
    }

    public function listAction(){
        $offset = $this->request->getQuery('offset', $this->offset);
        $limit = $this->request->getQuery('limit', $this->limit);
        $where = array();

        $data = array();
        $result = $this->memberArticleModel->getList($where, "memberArticleID desc", $offset, $limit);
        $data['total'] = $result['memberArticlesCount'];

        $data['rows'] = $result['memberArticles'];

        return $this->adminResponse($data);
    }

}