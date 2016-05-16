<?php
namespace Front\Controller;

use COM\Controller\Front;

class IndexController extends Front{

    public function indexAction(){

        return $this->view;
    }

    public function uploadAction(){
        $fileService = $this->sm->get('COM\Service\FileService');
        $result = $fileService->upload($this->request);
        if(!empty($result)) $result = json_decode($result, true);
        $imgPath = !empty($result[0]['path']) ? $result[0]['path'] : '';

        return $this->view;
    }

    public function contactAction(){
        return $this->view;
    }
}