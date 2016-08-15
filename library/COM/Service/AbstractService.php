<?php
namespace COM\Service;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

abstract class AbstractService implements ServiceManagerAwareInterface{
	protected $sm;
	
	public function __construct($sm = null){
		if($sm instanceof ServiceManager){
			$this->sm = $sm;
		}
	}

    /**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->sm = $serviceManager;
        // TODO: Implement setServiceManager() method.
    }


}