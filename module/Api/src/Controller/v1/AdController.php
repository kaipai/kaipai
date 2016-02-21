<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\BaseConst;
use Base\Functions\Utility;
use COM\Controller\Api;
class AdController extends Api{

    public function listAction(){
        $position = $this->postData['position'];
        $position = explode(',', $position);
        $where = array(
            'status' => BaseConst::AD_STATUS_ENABLE,
            'position' => $position,
        );

        $ads = $this->adModel->getList($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $ads);
    }

}