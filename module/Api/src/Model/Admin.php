<?php
namespace Api\Model;
use COM\Model\Api;

class Admin extends Api{
    protected $table = 'Admin';

    public function genPassword($pwd = null){
        return md5($pwd);
    }

}