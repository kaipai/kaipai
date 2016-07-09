<?php
namespace Admin\Controller;
use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;
use Zend\Db\Sql\Where;

class MemberArticleController extends Admin{
    public function indexAction(){
        return $this->view;
    }

    public function listAction(){
        $search = $this->queryData['search'];
        $sort = $this->queryData['sort'];
        $order = $this->queryData['order'];
        $where = new Where();
        if(!empty($search)){
            $where->or->like('MemberArticle.memberArticleName', '%' . $search . '%');
        }
        if(!empty($sort) && !empty($order)){
            $sortOrder = 'MemberArticle.' . $sort . ' ' . $order;
        }

        $data = array();
        $result = $this->memberArticleModel->getArticles($where, $this->pageNum, $this->limit, $sortOrder);
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

    public function updateAction(){
        $memberArticleID = $this->postData['memberArticleID'];

        $where = array(
            'memberArticleID' => $memberArticleID
        );

        $this->memberArticleModel->update($this->postData, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');
    }

}