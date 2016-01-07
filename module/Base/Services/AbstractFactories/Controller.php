<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 15/6/8
 * Time: 11:02
 */
namespace Base\Services\AbstractFactories;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Controller implements  AbstractFactoryInterface{
    protected $serviceControllerKey = '\Controller\\';

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {

        return (stripos($requestedName,$this->serviceControllerKey) !==false &&
            class_exists($requestedName.'Controller'));
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $controller =  $requestedName.'Controller';
        return new $controller;
    }

}