<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;

class ArtistController extends Admin{

    public function indexAction(){

        return $this->view;
    }

    public function addViewAction(){
        $artistID = $this->queryData['artistID'];
        $categories = $this->artistCategoryModel->select()->toArray();
        if(!empty($artistID)){
            $info = $this->artistModel->select(array('artistID' => $artistID))->current();
        }
        $this->view->setVariables(array(
            'categories' => $categories,
            'info' => $info
        ));
        return $this->view;
    }

    public function addAction(){

        if(!empty($this->postData['artistID'])){
            $update = $this->postData;
            unset($update['artistID']);
            $this->artistModel->update($update, array('artistID' => $this->postData['artistID']));
        }else{
            unset($this->postData['artistID']);
            $this->artistModel->insert($this->postData);
        }

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');

    }

    public function listAction(){

        $res = $this->artistModel->getArtists(array(), $this->pageNum, $this->limit);

        return $this->adminResponse(array('rows' => $res['data'], 'total' => $res['total']));
    }

    public function delAction(){
        $artistID = $this->postData['artistID'];

        $this->artistModel->update(array('isDel' => 1), array('artistID' => $artistID));

        return $this->response(AdminSuccess::COMMON_SUCCESS, '删除成功');
    }

}
