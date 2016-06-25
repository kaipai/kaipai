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
        $position = $this->request->getQuery('position');
        $where = array();

        $data = array();
        $where['position'] = $position;

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
        if(!empty($adData['startTime'])) $adData['startTime'] = strtotime($adData['startTime']);
        if(!empty($adData['endTime'])) $adData['endTime'] = strtotime($adData['endTime']);
        $this->adModel->insert($adData);

        return $this->response(AdminSuccess::COMMON_SUCCESS, AdminSuccess::COMMON_SUCCESS_MSG);
    }

    public function addViewAction(){
        $adID = $this->request->getQuery('adID');
        $position = $this->request->getQuery('position');

        if(!empty($adID)){
            $adInfo = $this->adModel->select(array('adID' => $adID))->current();
            $this->view->setVariable('adInfo', $adInfo);
            $position = $adInfo['position'];
        }

        $positions = BaseConst::$adPositions;
        $this->view->setVariable('positions', $positions);
        $this->view->setVariables(array(
            'position' => $position,
        ));
        $this->layout()->setVariables(array(
            'position' => $position,
        ));

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

    public function indexBannerAction(){
        $this->view->setVariables(array(
            'position' => 1,
        ));
        $this->view->setTemplate('/admin/ad/index');
        return $this->view;
    }

    public function indexSecondAction(){
        $this->view->setVariables(array(
            'position' => 2,
        ));
        $this->view->setTemplate('/admin/ad/index');
        return $this->view;
    }

    public function indexThirdLeftAction(){
        $this->view->setVariables(array(
            'position' => 3,
        ));
        $this->view->setTemplate('/admin/ad/index');
        return $this->view;
    }

    public function indexThirdRightAction(){
        $this->view->setVariables(array(
            'position' => 4,
        ));
        $this->view->setTemplate('/admin/ad/index');
        return $this->view;
    }

    public function productListLeftAction(){
        $this->view->setVariables(array(
            'position' => 5,
        ));
        $this->view->setTemplate('/admin/ad/index');
        return $this->view;
    }

    public function productListRightAction(){
        $this->view->setVariables(array(
            'position' => 6,
        ));
        $this->view->setTemplate('/admin/ad/index');
        return $this->view;
    }

    public function specialIndexAction(){
        $this->view->setVariables(array(
            'position' => 7,
        ));
        $this->view->setTemplate('/admin/ad/index');
        return $this->view;
    }

    public function loginAdAction(){
        $this->view->setVariables(array(
            'position' => 8,
        ));
        $this->view->setTemplate('/admin/ad/index');
        return $this->view;
    }

}
