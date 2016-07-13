<?php
namespace Base\Events;

use Zend\Di\ServiceLocator;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class Response implements ListenerAggregateInterface{

    public function attach(EventManagerInterface $events){
        $this->listeners[] = $events->attach('response', array($this, 'response'));


        $this->listeners[] = $events->attach('cache.pre', function($e) {
            $params = $e->getParams();
            $cache = $params['cache'];
            $key = md5(json_encode($params));
            $val = $cache->getFileItem($key);
            return $val;
        });
        $this->listeners[] = $events->attach('cache.post', function($e) {
            $params = $e->getParams();
            $cache = $params['cache'];
            $result = $params['__RESULT__'];
            unset($params['__RESULT__']);
            $key = md5(json_encode($params));

            $cache->setFileItem($key, $result);

        });
        //$this->listeners[] = $events->getSharedManager()->attach('*', 'response', array($this, 'response'));
    }

    public function detach(EventManagerInterface $events){

    }

    public function response($e){
        return 'do response';
    }

    public function call($foo, $bar){

    }
}
?>