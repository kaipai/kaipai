<?php
namespace Admin\Controller;
use Base\ConstDir\Admin\AdminError;
use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;
use Zend\Db\Sql\Where;

class SmsController extends Admin{
    public function smsAction(){


        return $this->view;
    }

    public function smsListAction(){
        $offset = $this->request->getQuery('offset', $this->offset);
        $limit = $this->request->getQuery('limit', $this->limit);
        $search = $this->queryData['search'];
        $where = new Where();
        if(!empty($search)){
            $where->and->nest()->or->like('SmsLog.mobile', '%' . $search . '%');
        }

        $data = array();

        $data['total'] = $this->smsLogModel->getCount($where);

        $data['rows'] = $this->smsLogModel->getList($where, "logID desc", $offset, $limit);

        return $this->adminResponse($data);
    }
}