<?php
namespace Front\Controller;

use Base\Cache\FileCache;
use Base\Events\Response;
use Base\Events\SubTest;
use Base\Events\Test;
use COM\Controller;
use Zend\Cache\Pattern\CallbackCache;
use Zend\Cache\Storage\Event;
use Zend\EventManager\EventManager;
use Zend\EventManager\SharedEventManager;

class TestController extends Controller{

    public function indexAction(){
        /*$eventManager = $this->getEventManager();

        $eventManager->attach('do', function($e){
            $event = $e->getName();
            $params = $e->getParams();
            printf('eventName %s, params %s', $event, json_encode($params));
        }, 5);
        $eventManager->attach('do', function($e){
            $event = $e->getName();
            $params = $e->getParams();
            printf('eventName %s, params %s44444', $event, json_encode($params));
        }, 4);
        $params = array('foo' => '111', 'bar' => '222');
        $eventManager->trigger('do', null, $params);*/





        /*$test = new Test();
        $test->getEventManager()->attach('doit', function($e){
            $event = $e->getName();
            $target = get_class($e->getTarget());
            $params = $e->getParams();
            printf('eventName %s, target %s, params %s', $event, $target, json_encode($params));
        });

        $test->doit('1111', '2222');*/




        /*$sharedEventManager = new SharedEventManager();
        $sharedEventManager->attach('Base\Events\Test', 'back', function($e){
            $event = $e->getName();
            $target = get_class($e->getTarget());
            $params = $e->getParams();
            printf('eventName %s, target %s, params %s', $event, $target, json_encode($params));
        });

        $test = new Test();
        $test->getEventManager()->setSharedManager($sharedEventManager);
        $test->doit('1111', '2222');*/


        /*$eventManage = new EventManager();
        // 参数1:事件名称, 参数2:回调
        $eventManage->attach(array('foo', 'bar', 'baz'), $listener);
        $eventManage->attach('*', $listener);
        $sharedEventManager = new SharedEventManager();
        // 参数1:targets, 参数2:事件名称, 参数3:回调
        $sharedEventManager->attach(array('Foo', 'Bar', 'Baz'), $eventName, $listener);
        $sharedEventManager->attach(array('Foo', 'Bar', 'Baz'), array('foo', 'bar', 'baz'), $listener);
        $sharedEventManager->attach(array('Foo', 'Bar', 'Baz'), '*', $listener);
        $sharedEventManager->attach('*', array('foo', 'bar', 'baz'), $listener);
        $sharedEventManager->attach('*', '*', $listener);*/

        /*$responseListener = new Response();
        $eventManager = $this->getEventManager();
        $eventManager->attach($responseListener);

        $eventManager->trigger('response', null, array(), function($data){
            var_dump($data);
        });*/



        die();




        return $this->response;
    }

    public function cacheAction(){

        $eventManager = $this->getEventManager();
        $listeners = new Response();
        $eventManager->attach($listeners);
        $fileCache = new FileCache($this->sm);
        $params = array(
            'foo' => 111,
            'bar' => 222,
            'cache' => $fileCache
        );
        $results = $eventManager->trigger('cache.pre', $this, $params, function($data){
            return !empty($data);
        });

        if($results->stopped()){
            var_dump($results);
            var_dump($results->last());
        }else{
            $calculatedResult = 444;
            $params['__RESULT__'] = $calculatedResult;
            $eventManager->trigger('cache.post', $this, $params);
            var_dump($calculatedResult);
        }



        die;
    }

    public function testAction(){

        if($event == 'response'){
            $f = fopen('text.txt', 'a+');
            ob_start();
            echo '<pre>';
            print_r($this->events);
            $str = ob_get_contents();
            ob_clean();
            fwrite($f, $str);
            fclose($f);

        }
    }
}
?>