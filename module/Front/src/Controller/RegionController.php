<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Front;

class RegionController extends Front{

    public function listAction(){

        $pid = !empty($this->postData['pid']) ? $this->postData['pid'] : 1;

        $regions = $this->regionModel->getRegionsByPid($pid);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $regions);
    }

}