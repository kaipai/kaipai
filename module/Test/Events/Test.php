<?php
namespace Base\Events;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class Test implements EventManagerAwareInterface{
    protected $eventManager;

    public function getEventManager(){
        if(empty($this->eventManager)){
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
    }

    public function setEventManager(EventManagerInterface $eventManager){
        $eventManager->setIdentifiers(array(
            __CLASS__
        ));

        $this->eventManager = $eventManager;

    }

    public function doit($foo, $bar){

        $params = compact(array('foo', 'bar'));
        $this->getEventManager()->trigger('back', $this, $params);

    }

}