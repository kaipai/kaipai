<?php
namespace Admin\Controller;
use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class MemberArticleController extends Admin{
    public function indexAction(){
        return $this->view;
    }

    public function listAction(){
        $where = array();

        $data = array();
        $result = $this->memberArticleModel->getArticles($where, $this->pageNum, $this->limit);
        $rows = $result['data'];

        foreach($rows as $k => $v){
            $rows[$k]['memberArticleContent'] = '/zone/article-detail?zoneID=' . $v['memberID'] . '&memberArticleID=' . $v['memberArticleID'];
        }
        $data['total'] = $result['total'];

        $data['rows'] = $rows;

        return $this->adminResponse($data);
    }

    public function delAction(){
        $memberArticleID = $this->postData['memberArticleID'];

        $this->memberArticleModel->update(array('isDel' => 1), array('memberArticleID' => $memberArticleID));

        return $this->response(AdminSuccess::COMMON_SUCCESS, '删除成功');
    }

}