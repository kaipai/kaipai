<?php
namespace Admin\Controller;
use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;
use Zend\Db\Sql\Predicate\Like;

class MemberArticleCommentController extends Admin{
    public function indexAction(){
        return $this->view;
    }

    public function listAction(){
        $where = array(
            'pid' => 0,
        );
        $search = $this->queryData['search'];
        $sort = $this->queryData['sort'];
        $order = $this->queryData['order'];
        if(!empty($search)){
            $where[] = new Like('c.memberArticleName', '%' . $search . '%');
        }
        if(!empty($sort) && !empty($order)){
            $sortOrder = 'MemberArticleComment.' . $sort . ' ' . $order;
        }

        $data = array();
        $result = $this->memberArticleCommentModel->getComments($where, $this->pageNum, $this->limit, $sortOrder);
        $rows = $result['data'];

        $data['total'] = $result['total'];

        $data['rows'] = $rows;

        return $this->adminResponse($data);
    }

    public function delAction(){
        $memberArticleCommentID = $this->postData['memberArticleCommentID'];

        $this->memberArticleCommentModel->delete(array('memberArticleCommentID' => $memberArticleCommentID));

        return $this->response(AdminSuccess::COMMON_SUCCESS, '删除成功');
    }

    public function updateAction(){
        $memberArticleCommentID = $this->postData['memberArticleCommentID'];

        $where = array(
            'memberArticleCommentID' => $memberArticleCommentID
        );

        $this->memberArticleCommentModel->update($this->postData, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');
    }

}