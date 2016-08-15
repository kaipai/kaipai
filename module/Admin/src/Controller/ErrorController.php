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
        $sort = $this->queryData['sort'];
        $order = $this->queryData['order'];
        if(!empty($sort) && !empty($order)){
            $sortOrder = 'Error.' . $sort . ' ' . $order;
        }
        $res = $this->errorModel->getErrors($this->pageNum, $this->limit, $sortOrder);

        return $this->adminResponse(array('rows' => $res['data'], 'total' => $res['total']));
    }

    public function updateAction(){
        $errorID = $this->postData['errorID'];

        $where = array(
            'errorID' => $errorID
        );

        $this->errorModel->update($this->postData, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');
    }

}