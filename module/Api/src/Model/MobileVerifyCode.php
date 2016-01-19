<?php
namespace Api\Model;

use COM\Model;
class MobileVerifyCode extends Model{

    public function getVerifyCode(){
        $randStr = str_shuffle('1234567890');
        $code = substr($randStr,0,5);
        return $code;
    }
}