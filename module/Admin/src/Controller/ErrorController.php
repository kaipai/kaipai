<?php
namespace Admin\Controller;
use Base\ConstDir\Admin\AdminError;
use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class ErrorController extends Admin{
    public function indexAction(){

        return $this->view;
    }

    public function listAction(){

        $res = $this->errorModel->getErrors($this->pageNum, $this->limit);

        return $this->adminResponse(array('rows' => $res['data'], 'total' => $res['total']));
    }

}