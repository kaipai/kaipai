<?php
namespace COM\Controller;

use COM\Controller;

class Admin extends Controller{

    public function preDispatch()
    {
        parent::preDispatch();
        $this->layout('adminLayout');
    }
}