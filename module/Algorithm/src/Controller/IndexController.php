<?php
namespace Algorithm\Controller;

use COM\Controller;

class IndexController extends Controller{

    public function indexAction(){

        echo 1111;

        return $this->response;
    }
}