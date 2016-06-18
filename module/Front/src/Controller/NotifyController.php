<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\BaseConst;
use COM\Controller\Front;

class NotifyController extends Front{

    public function qqLoginAction(){

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }
}