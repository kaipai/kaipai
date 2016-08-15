<?php
namespace COM;
use Zend\View\Model\ViewModel;

class View extends ViewModel
{
    public function __construct($variables = null, $options = null){
        parent::__construct($variables = null, $options = null);
    }

    /**
     * 设置无layout布局
     */
    public function setNoLayout(){
        $this->setTerminal(true);
    }
}