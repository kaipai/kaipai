<?php
namespace Front\Controller;

use Base\ConstDir\BaseConst;
use COM\Controller\Front;

class AdController extends Front{

    public function listAction(){
        $position = $this->postData['position'];
        $position = explode(',', $position);
        $where = array(
            'status' => BaseConst::AD_STATUS_ENABLE,
            'position' => $position,
        );

        $ads = $this->adModel->getList($where);


        return $this->view;
    }

}