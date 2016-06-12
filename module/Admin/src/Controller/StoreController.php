<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminError;
use Base\ConstDir\Api\ApiError;
use COM\Controller\Admin;
use Base\ConstDir\BaseConst;
use Base\ConstDir\Admin\AdminSuccess;

class StoreController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $res = $this->storeModel->getStores($this->pageNum, $this->limit);

        return $this->adminResponse(array('rows' => $res['data'], 'total' => $res['total']));
    }

    public function updateAction(){
        $storeID = $this->postData['storeID'];

        $where = array(
            'storeID' => $storeID
        );
        unset($this->postData['storeID']);
        $this->storeModel->update($this->postData, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');
    }

}
