<?php
namespace Api\Controller\v1;

use Base\Functions\Utility;
use COM\Controller\Api;
class MemberController extends Api{
    private $_memberModel;
    public function init(){
        $this->_memberModel = $this->sm->get('Api\Model\Member');
    }

}