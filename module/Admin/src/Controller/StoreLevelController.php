<?php
namespace Admin\Controller;
use Base\ConstDir\Admin\AdminError;
use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class StoreLevelController extends Admin{
    public function indexAction(){

        return $this->view;
    }

    public function addViewAction(){
        $level = $this->queryData['level'];

        $info = $this->storeLevelModel->select(array('level' => $level))->current();
        $this->view->setVariables(array(
            'info' => $info
        ));
        return $this->view;
    }

    public function listAction(){

        $res = $this->storeLevelModel->select()->toArray();

        $this->adminResponse(array('rows' => $res, 'total' => count($res)));

        return $this->response;
    }

    public function updateAction(){
        $level = $this->postData['level'];

        $this->storeLevelModel->update($this->postData, array('level' => $level));

        return $this->response(AdminSuccess::COMMON_SUCCESS, '删除成功');
    }
}