<?php
namespace Front\Controller;

use Base\ConstDir\BaseConst;
use COM\Controller\Front;

class IndexController extends Front{

    public function indexAction(){

        $products = $this->productModel->getRecommendProducts();

        $this->view->setVariables(array(
            'products' => $products['products'],
        ));
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