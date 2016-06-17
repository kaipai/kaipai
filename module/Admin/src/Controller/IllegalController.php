<?php
namespace Admin\Controller;
use Base\ConstDir\Admin\AdminError;
use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class IllegalController extends Admin{
    public function indexAction(){

        return $this->view;
    }

    public function listAction(){

        $res = $this->illegalModel->getIllegals($this->pageNum, $this->limit);
        $data = $res['data'];
        foreach($data as $k => $v){
            if($v['type'] == 1){
                $data[$k]['content'] = $v['memberArticleName'];
            }
        }

        return $this->adminResponse(array('rows' => $data, 'total' => $res['total']));
    }

}