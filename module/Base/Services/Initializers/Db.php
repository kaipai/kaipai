<?php
namespace Base\Services\Initializers;

use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\InitializerInterface;

class Db implements InitializerInterface{

    public function initialize($instance, ServiceLocatorInterface $serviceLocator){

        if($instance instanceof AdapterAwareInterface && !is_null($instance->table)){
            $instance->setDbAdapter($serviceLocator->get('Zend\Db\Adapter\Adapter'));
        }
    }
}