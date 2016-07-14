<?php
namespace Test\Events;

use Zend\Di\ServiceLocator;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class TestListener implements ListenerAggregateInterface{

    public function attach(EventManagerInterface $events){
        $this->listeners[] = $events->attach('doit', array($this, 'doit'));

        $this->listeners[] = $events->attach('doit', array($this, 'doit2'));

        $this->listeners[] = $events->attach('cache.pre', function($e) {
            $params = $e->getParams();
            $target = $e->getTarget();
            $cache = $target->_fileCache;
            $key = md5(json_encode($params));
            $val = $cache->getFileItem($key);
            return $val;
        });
        $this->listeners[] = $events->attach('cache.post', function($e) {
            $params = $e->getParams();
            $target = $e->getTarget();
            $cache = $target->_fileCache;
            $result = $params['data'];
            unset($params['data']);
            $key = md5(json_encode($params));

            $cache->setFileItem($key, $result);

        });
    }

    public function detach(EventManagerInterface $events){

    }

    public function doit($e){
        $event = $e->getName();
        $target = get_class($e->getTarget());
        $params = $e->getParams();

        printf("eventName %s, target %s, process %s, params %s\n", $event, $target, __FUNCTION__, json_encode($params));
    }

    public function doit2($e){
        $event = $e->getName();
        $target = get_class($e->getTarget());
        $params = $e->getParams();
        printf("eventName %s, target %s, process %s, params %s", $event, $target, __FUNCTION__, json_encode($params));
    }
}
?>