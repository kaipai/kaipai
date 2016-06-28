<?php
namespace Admin\Controller;
use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class MemberArticleCommentController extends Admin{
    public function indexAction(){
        return $this->view;
    }

    public function listAction(){
        $where = array(
            'pid' => 0,
        );

        $data = array();
        $result = $this->memberArticleCommentModel->getComments($where, $this->pageNum, $this->limit);
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

}