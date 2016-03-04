<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\BaseConst;
use Base\Functions\Utility;
use COM\Controller\Api;
class IndexController extends Api{

    public function indexAction(){


        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function uploadAction(){
        $fileService = $this->sm->get('COM\Service\FileService');
        $result = $fileService->upload($this->request);
        if(!empty($result)) $result = json_decode($result, true);
        $imgPath = !empty($result[0]['path']) ? $result[0]['path'] : '';
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $imgPath);
    }

}