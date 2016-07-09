<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminError;
use Base\ConstDir\Api\ApiError;
use COM\Controller\Admin;
use Base\ConstDir\BaseConst;
use Base\ConstDir\Admin\AdminSuccess;
use Zend\Db\Sql\Where;

class StoreController extends Admin{

    public function indexAction(){
        $levels = $this->storeLevelModel->select()->toArray();

        $this->view->setVariables(array('levels' => $levels));
        return $this->view;
    }

    public function listAction(){
        $search = $this->queryData['search'];
        $where = new Where();
        if(!empty($search)){
            $where->or->like('Store.storeName', '%' . $search . '%');
        }
        $res = $this->storeModel->getStores($this->pageNum, $this->limit, $where);


        return $this->adminResponse(array('rows' => $res['data'], 'total' => $res['total']));
    }

    public function updateAction(){
        $storeID = $this->postData['storeID'];

        $where = array(
            'storeID' => $storeID
        );
        unset($this->postData['storeID']);
        $storeInfo = $this->storeModel->select($where)->current();
        if($this->postData['verifyStatus'] == 2){
            $this->notificationModel->insert(array('type' => 2, 'memberID' => $storeInfo['memberID'], 'content' => '您的加盟申请已通过。'));
        }elseif($this->postData['verifyStatus'] == 3){
            $this->notificationModel->insert(array('type' => 2, 'memberID' => $storeInfo['memberID'], 'content' => '您的加盟申请未通过，请修改。'));
        }
        $this->storeModel->update($this->postData, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');
    }

    public function closeAction(){
        $storeID = $this->postData['storeID'];

        $where = array(
            'storeID' => $storeID
        );
        $this->postData['storeCloseTime'] = time();
        $this->storeModel->update($this->postData, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');
    }

}
