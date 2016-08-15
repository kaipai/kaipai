<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;
use Zend\Db\Sql\Where;

class MemberController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $offset = $this->request->getQuery('offset', $this->offset);
        $limit = $this->request->getQuery('limit', $this->limit);
        $search = $this->queryData['search'];
        $sort = $this->queryData['sort'];
        $order = $this->queryData['order'];
        $where = new Where();
        if(!empty($search)){
            $where->and->nest()->or->like('MemberInfo.mobile', '%' . $search . '%')->or->like('MemberInfo.nickName', '%' . $search . '%');
        }
        if(!empty($sort) && !empty($order)){
            $sortOrder = $sort . ' ' . $order;
        }else{
            $sortOrder = 'memberID desc';
        }

        $data = array();

        $data['total'] = $this->memberInfoModel->getCount($where);

        $data['rows'] = $this->memberInfoModel->getList($where, $sortOrder, $offset, $limit);

        return $this->adminResponse($data);
    }

    public function updateAction(){
        $memberID = $this->postData['memberID'];

        $where = array(
            'memberID' => $memberID
        );
        unset($this->postData['memberID']);

        $this->memberInfoModel->update($this->postData, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');
    }

    public function closeAction(){
        $memberID = $this->postData['memberID'];

        $where = array(
            'memberID' => $memberID
        );
        $this->postData['closeTime'] = time();
        $this->memberInfoModel->update($this->postData, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');
    }
}
