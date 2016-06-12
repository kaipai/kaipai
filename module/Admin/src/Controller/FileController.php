<?php
namespace Admin\Controller;

use COM\Controller\Admin;

class FileController extends Admin{
    function fileUploadAction(){
        $fileStr = $this->sm->get('COM\Service\FileService')->upload($this->request);
        $fileArr = \Zend\Json\Json::decode($fileStr,\Zend\Json\Json::TYPE_ARRAY);
        ///print_r($fileArr);exit;
        $filePath = $_SERVER['DOCUMENT_ROOT'].$fileArr[0]['path'];
        $data = array();
        $data['jsonrpc'] = '2.0';
        $data['result'] = '成功';
        $data['id'] = 6;
        $data['url'] = 'http://'.$_SERVER['HTTP_HOST'].$fileArr[0]['path'];
        $data['path'] = $fileArr[0]['path'];
        echo json_encode($data);
        return $this->response;
    }
}