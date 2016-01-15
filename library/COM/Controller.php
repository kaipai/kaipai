<?php
namespace COM;

use Zend\Db\Sql\Select;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\Storage\Session;
use Base\Functions\Utility;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class Controller extends AbstractActionController{

    protected $view;
    protected $sm;
    protected $route;
    protected $loginInfo;
    protected $postData;
    protected $controllerName;
    protected $actionName;
    protected $offset;
    protected $limit;
    const FRONT_PLATFORM = 'FRONT';
    const ADMIN_PLATFORM = 'ADMIN';

    public function init(){
    }

    public function __construct(){
        $this->view = new View();
        $em = $this->getEventManager();
        $em->attach(MvcEvent::EVENT_DISPATCH, array($this, 'preDispatch'), 100);
        $em->attach(MvcEvent::EVENT_DISPATCH, array($this, 'init'), 99);
    }

    public function preDispatch(){
        $this->sm = $this->getServiceLocator();
        $event = $this->getEvent();
        $this->route = $event->getRouteMatch();

        $this->postData = $this->request->getPost()->toArray();
        $this->controllerName = $this->route->getParam('__CONTROLLER__');
        $this->actionName = $this->route->getParam('action');

    }

    protected function response($flag = null, $msg = null, $data = null, $url = null){
        $headers = array(
            'Access-Control-Allow-Origin: *',
            'Access-Control-Allow-Headers: Content-type',
            'Content-type:application/json;charset=utf-8'
        );
        $this->response->getHeaders()->addHeaders($headers);

        $response = array(
            'flag' => $flag
        );
        if(!empty($msg)) $response['msg'] = $msg;
        if(!empty($data)) $response['data'] = $data;
        if(!empty($url)) $response['url'] = $url;
        $this->response->setContent(json_encode($response, JSON_UNESCAPED_UNICODE));
        return $this->response;
    }

    /**
     * 验证登录
     * @param $platform
     * @return bool|mixed
     */
    protected function checkLogin($platform = self::WEB_PLATFORM){
        $loginSession = new Session($platform, null,null);
        $this->loginInfo = $session = $loginSession->read();
        if(empty($session)){
            return false;
        }else{
            return $session;
        }
    }

    /**
     * 根据参数生成签名
     */
    protected function genSignature(){
        $posts = $this->request->getPost();
        unset($posts['Signature']);
        $signature = 'sign:';
        if(!empty($posts)){
            foreach($posts as $k => $v){
                $signature .= $k . $v;
            }
        }
        return md5($signature);
    }

    /**
     * 过滤传入参数使其符合数据模型需要, 验证必需参数是否传入
     * @param array $parameters 传入参数
     * @param array $purview 数据模型需要的参数
     * @param array $required 必需参数
     * @return array|bool
     */
    protected function parametersFilter($parameters = array(), $purview = array(), $required = array())
    {
        $data = array();
        $flag = true;
        foreach ($purview as $v) {
            if (in_array($v, $required) && empty($parameters[$v])) {
                $flag = false;
            }
            $parameters[$v] = trim($parameters[$v]);
            if (!empty($parameters[$v]) || $parameters[$v] === 0 || $parameters[$v] === '0') {
                $data[$v] = $parameters[$v];
            }
        }
        if(empty($data)){
            $flag = false;
        }
        if(!$flag){
            return false;
        }
        return $data;
    }

}
