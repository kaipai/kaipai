<?php
namespace Base\Events;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class Response implements ListenerAggregateInterface{

    public function attach(EventManagerInterface $events){
        //$this->listeners[] = $events->attach('response', array($this, 'response'));
        //$this->listeners[] = $events->getSharedManager()->attach('*', 'response', array($this, 'response'));
    }

    public function detach(EventManagerInterface $events){

    }

    public function response($sm){
        var_Dump('123');die;

        return 11111;
    }
}
?>