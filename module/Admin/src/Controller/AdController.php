<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use Base\ConstDir\BaseConst;
use COM\Controller\Admin;

class AdController extends Admin{
    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $offset = $this->request->getQuery('offset', $this->offset);
        $limit = $this->request->getQuery('limit', $this->limit);
        $where = array();

        $data = array();

        $data['total'] = $this->adModel->getCount($where);

        $rows = $this->adModel->getList($where, "adID desc", $offset, $limit);
        foreach($rows as $k => $v){
            $rows[$k]['position'] = BaseConst::$adPositions[$v['position']];
            $rows[$k]['startTime'] = date('Y-m-d H:i:s', $v['startTime']);
            $rows[$k]['endTime'] = date('Y-m-d H:i:s', $v['endTime']);
        }
        $data['rows'] = $rows;
        return $this->adminResponse($data);
    }

    public function addAction(){
        $adData = $this->postData;
        unset($adData['adID']);
        $this->adModel->insert($adData);

        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
    }

    public function addViewAction(){
        $adID = $this->request->getQuery('adID');
        if(!empty($adID)){
            $adInfo = $this->adModel->select(array('adID' => $adID))->current();
            $this->view->setVariable('adInfo', $adInfo);
        }

        $positions = BaseConst::$adPositions;
        $this->view->setVariable('positions', $positions);

        return $this->view;
    }

    public function updateAction(){
        $adID = $this->postData['adID'];
        $set = $this->postData;
        $where = array('adID' => $adID);
        if(!empty($set['startTime'])) $set['startTime'] = strtotime($set['startTime']);
        if(!empty($set['endTime'])) $set['endTime'] = strtotime($set['endTime']);
        $this->adModel->update($set, $where);
        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
    }

    public function delAction(){
        $where = array(
            'adID' => $this->postData['adID']
        );

        $this->adModel->update(array('isDel' => 1), $where);
        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
    }

}
