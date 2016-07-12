<?php
namespace Front\Controller;

use COM\Controller;

class TestController extends Controller{

    public function indexAction(){
        $this->getEventManager()->trigger('response', $this, array(), function($data){
            var_dump($data);
        });

        return $this->response;
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