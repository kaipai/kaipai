<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class SpecialController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){

        $res = $this->specialModel->getSpecials(array(), $this->pageNum, $this->limit);
        $rows = $res['data'];
        foreach($rows as $k => $v){
            $rows[$k]['startTime'] = date('Y-m-d H:i:s', strtotime($v['startTime']));
            $rows[$k]['endTime'] = date('Y-m-d H:i:s', strtotime($v['endTime']));
        }

        return $this->adminResponse(array('rows' => $rows, 'total' => $res['total']));
    }

    public function updateAction(){
        $specialID = $this->postData['specialID'];

        $where = array(
            'specialID' => $specialID
        );
        unset($this->postData['specialID']);
        $this->specialModel->update($this->postData, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');
    }
}
