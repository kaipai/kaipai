<?php
namespace Admin\Controller;
use Base\ConstDir\Admin\AdminError;
use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class CustomizationController extends Admin{
    public function indexAction(){

        return $this->view;
    }

    public function addViewAction(){
        $customizationID = $this->queryData['customizationID'];
        $artists = $this->artistModel->select(array('isDel' => 0))->toArray();
        if(!empty($customizationID)){
            $info = $this->customizationModel->select(array('customizationID' => $customizationID))->current();
        }
        $this->view->setVariables(array(
            'artists' => $artists,
            'info' => $info
        ));
        return $this->view;
    }

    public function addAction(){
        if(!empty($this->postData['startTime'])) $this->postData['startTime'] = strtotime($this->postData['startTime']);
        if(!empty($this->postData['endTime'])) $this->postData['endTime'] = strtotime($this->postData['endTime']);

        if(!empty($this->postData['customizationID'])){
            $update = $this->postData;
            unset($update['customizationID']);
            $this->customizationModel->update($update, array('customizationID' => $this->postData['customizationID']));
        }else{
            unset($this->postData['customizationID']);
            $this->customizationModel->insert($this->postData);
        }

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');

    }

    public function listAction(){

        $res = $this->customizationModel->getCustomizations($this->pageNum, $this->limit);
        foreach($res['data'] as $k => $v){
            $res['data'][$k]['startTime'] = date('Y-m-d H:i:s', $v['startTime']);
            $res['data'][$k]['endTime'] = date('Y-m-d H:i:s', $v['endTime']);
        }
        $this->adminResponse(array('rows' => $res['data'], 'total' => $res['total']));

        return $this->response;
    }

    public function delAction(){
        $customizationID = $this->postData['customizationID'];

        $this->customizationModel->update(array('isDel' => 1), array('customizationID' => $customizationID));

        return $this->response(AdminSuccess::COMMON_SUCCESS, '删除成功');
    }
}