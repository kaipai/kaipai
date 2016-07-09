<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminError;
use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Where;

class SpecialController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $search = $this->queryData['search'];
        $where = new Where();
        if(!empty($search)){
            $where->or->like('Special.specialName', '%' . $search . '%');
        }
        $res = $this->specialModel->getSpecials($where, $this->pageNum, $this->limit);
        $rows = $res['data'];
        foreach($rows as $k => $v){
            $rows[$k]['startTime'] = date('Y-m-d H:i:s', $v['startTime']);
            $rows[$k]['endTime'] = date('Y-m-d H:i:s', $v['endTime']);
        }

        return $this->adminResponse(array('rows' => $rows, 'total' => $res['total']));
    }

    public function updateAction(){
        $specialID = $this->postData['specialID'];
        if(empty($specialID)) return $this->response(AdminError::COMMON_ERROR, '缺少参数');
        $where = array(
            'specialID' => $specialID
        );
        unset($this->postData['specialID']);
        $specialInfo = $this->specialModel->getFullInfo($where);

        if($this->postData['verifyStatus'] == 2){
            $this->notificationModel->insert(array('type' => 3, 'memberID' => $specialInfo['memberID'], 'content' => '您的<<' . $specialInfo['specialName'] . '>>专场审核已通过。'));
            $this->productModel->update(array('auctionStatus' => 1, 'startTime' => $specialInfo['startTime'], 'endTime' => $specialInfo['endTime'], 'isPaid' => 1), $where);
        }elseif($this->postData['verifyStatus'] == 3){
            $this->notificationModel->insert(array('type' => 3, 'memberID' => $specialInfo['memberID'], 'content' => '您的<<' . $specialInfo['specialName'] . '>>专场审核未通过，请修改。'));
            $this->productModel->update(array('auctionStatus' => 0, 'startTime' => new Expression('null'), 'endTime' => new Expression('null')), $where);
        }

        $this->specialModel->update($this->postData, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');
    }

    public function withdrawAction(){
        $specialID = $this->postData['specialID'];

        $this->specialModel->withdraw($specialID);
        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');

    }
}
