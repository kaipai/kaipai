<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminError;
use Base\ConstDir\Admin\AdminSuccess;
use Base\ConstDir\BaseConst;
use COM\Controller\Admin;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Where;

class WithdrawController extends Admin{
    public function indexAction(){

        $password = $this->postData['password'];
        $tmp = $this->siteSettingModel->select(array('settingName' => 'withdrawPassword'))->current();

        if(empty($password) || $password != $tmp['settingValue']){
            return $this->redirect()->toUrl('/admin/withdraw/password');
        }

        return $this->view;
    }

    public function listAction(){
        $search = $this->queryData['search'];
        $where = new Where();
        if(!empty($search)){
            $where->or->like('b.storeName', '%' . $search . '%');
        }
        $res = $this->withdrawLogModel->getLogs($where, $this->pageNum, $this->limit);
        $logs = $res['data'];

        $data['total'] = $res['total'];
        $data['rows'] = $logs;
        return $this->adminResponse($data);
    }

    public function verifyAction(){
        $verifyStatus = $this->postData['verifyStatus'];
        $logID = $this->postData['logID'];
        $logInfo = $this->withdrawLogModel->select(array('logID' => $logID))->current();
        if(empty($logInfo)) return $this->response(AdminError::COMMON_ERROR, '不存在该记录');
        if($verifyStatus == 2){
            $this->withdrawLogModel->update(array('verifyStatus' => 2), array('logID' => $logInfo['logID']));
        }elseif($verifyStatus == 3){
            try{
                $this->memberInfoModel->beginTransaction();
                $this->withdrawLogModel->update(array('verifyStatus' => 3), array('logID' => $logInfo['logID']));
                $this->memberInfoModel->update(array('rechargeMoney' => new Expression('rechargeMoney + ' . $logInfo['money'])), array('memberID' => $logInfo['memberID']));
                $this->memberRechargeMoneyLogModel->insert(array('memberID' => $logInfo['memberID'], 'money' => $logInfo['money'], 'source' => '提现审核失败, 退回到余额', 'type' => 1));
                $this->memberInfoModel->commit();
            }catch (\Exception $e){
                $this->memberInfoModel->rollback();
                return $this->response(AdminError::COMMON_ERROR, '审核失败');
            }
        }
        return $this->response(AdminSuccess::COMMON_SUCCESS, '审核成功');
    }

    public function rechargeLogAction(){
        $this->view->setNoLayout();
        $memberID = $this->queryData['memberID'];
        $where = array(
            'MemberRechargeMoneyLog.memberID' => $memberID,
        );

        $res = $this->memberRechargeMoneyLogModel->getLogs($where, $this->pageNum, 1000);
        $logs = $res['data'];

        $this->view->setVariables(array(
            'logs' => $logs,
        ));


        return $this->view;
    }

    public function passwordAction(){

        return $this->view;
    }

}
