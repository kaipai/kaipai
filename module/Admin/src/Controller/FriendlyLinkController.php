<?php
namespace Admin\Controller;
use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class FriendlyLinkController extends Admin{
    public function indexAction(){
        return $this->view;
    }

    public function addViewAction(){
        $linkID = $this->queryData['linkID'];
        if(!empty($linkID)){
            $info = $this->friendlyLinkModel->select(array('linkID' => $linkID))->current();
        }
        $this->view->setVariables(array(
            'info' => $info
        ));
        return $this->view;
    }

    public function addAction(){

        if(!empty($this->postData['linkID'])){
            $update = $this->postData;
            unset($update['linkID']);
            $this->friendlyLinkModel->update($update, array('linkID' => $this->postData['linkID']));
        }else{
            unset($this->postData['linkID']);
            $this->friendlyLinkModel->insert($this->postData);
        }

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');

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

    public function delAction(){
        $linkID = $this->postData['linkID'];

        $this->friendlyLinkModel->delete(array('linkID' => $linkID));

        return $this->response(AdminSuccess::COMMON_SUCCESS, '删除成功');
    }
}