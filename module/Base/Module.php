<?php
namespace Base;

use Zend\Db\TableGateway\Feature\EventFeature;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ControllerProviderInterface
{

    public function onBootstrap(MvcEvent $e)
    {

        /**
         * log exceptions
         */
        $eventManager = $e->getApplication()->getEventManager();
        $sharedManager = $eventManager->getSharedManager();
        $sm = $e->getApplication()->getServiceManager();
        $sharedManager->attach('Zend\Mvc\Application', 'dispatch.error',
            function ($e) use ($sm) {
                $reason = $e->getParam('exception');
                !empty($reason) ?: $reason = $e->getParam('error') . ': "' . $e->getRequest()->getUriString() . '"';
                $sm->get('logger')->log($reason);
            }
        );
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), 100);
        $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, array($this, 'onDispatchError'), 100);

    }

    public function onDispatchError(MvcEvent $e)
    {
        $viewModel = $e->getViewModel();
        $viewModel->setTemplate('layout/error');
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(

            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/AutoloadClassmap.php',
            ),

            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to seed
     * such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getControllerConfig()
    {
        return array(
            'abstract_factories' => array(
                'Base\Services\AbstractFactories\Controller',
            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            'abstract_factories' => array(
                'Base\Services\AbstractFactories\Model',
                'Base\Services\AbstractFactories\Service'
            )
        );
    }


}