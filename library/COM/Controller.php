<?php
namespace COM;

use Zend\Console\Request;
use Zend\Db\Sql\Select;
use Zend\Http\Cookies;
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
    protected $memberInfo;
    protected $adminInfo;
    protected $postData;
    protected $queryData;
    protected $controllerName;
    protected $actionName;
    protected $pageNum;
    protected $offset;
    protected $limit;
    protected $order;
    protected $sort;
    protected $siteSettings;
    protected $dataType;
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
        if(!$this->request instanceof Request){
            $this->postData = $this->request->getPost()->toArray();
            $this->queryData = $this->request->getQuery()->toArray();
        }

        $this->controllerName = $this->route->getParam('__CONTROLLER__');
        $this->actionName = $this->route->getParam('action');
        $this->pageNum = $this->postData['pageNum'] ? $this->postData['pageNum'] : $this->queryData['pageNum'];
        $this->limit = $this->postData['limit'] ? $this->postData['limit'] : $this->queryData['limit'];
        $this->offset = $this->postData['offset'] ? $this->postData['offset'] : $this->queryData['offset'];
        $this->dataType = $this->postData['dataType'] ? $this->postData['dataType'] : $this->queryData['dataType'];
        if(!empty($this->pageNum) && !empty($this->limit)){
            $this->offset = ($this->pageNum - 1) * $this->limit;
        }elseif(!empty($this->offset)){
            $this->pageNum = ($this->offset + $this->limit) / $this->limit;
        }

        $this->order = $this->postData['order'];
        $this->sort = $this->postData['sort'];

    }

    protected function response($flag = null, $msg = null, $data = null, $url = '/'){
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

    protected function adminResponse($data = array()){
        $headers = array(
            'Access-Control-Allow-Origin: *',
            'Access-Control-Allow-Headers: Content-type',
            'Content-type:application/json;charset=utf-8'
        );
        $this->response->getHeaders()->addHeaders($headers);

        $this->response->setContent(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $this->response;
    }

    /**
     * 验证登录
     * @param $platform
     * @return bool|mixed
     */
    protected function checkLogin($platform = self::FRONT_PLATFORM){

        if($platform == self::FRONT_PLATFORM){
            /*$token = $this->postData['token'];
            if (!empty($token)){
                $tokenInfo = $this->tokenModel->select(array('token' => $token))->current();
                if(!empty($tokenInfo)){
                    $this->memberInfo = $this->memberInfoModel->select(array('memberID' => $tokenInfo['memberID']))->current();
                }

            }*/
            $autoCode = $_COOKIE['autoCode'];

            if(!empty($autoCode)){
                $this->memberInfo = $this->memberInfoModel->select(array('autoCode' => $autoCode))->current();
            }else{
                $loginSession = new Session($platform, null,null);

                $this->memberInfo = $session = $loginSession->read();
                if(!empty($this->memberInfo['memberID'])){
                    $this->memberInfo = $this->memberInfoModel->select(array('memberID' => $this->memberInfo['memberID']))->current();
                }

            }


        }else{
            $loginSession = new Session($platform, null,null);
            $this->adminInfo = $session = $loginSession->read();
        }

        if(empty($this->memberInfo) && empty($this->adminInfo)){
            return false;
        }else{
            return true;
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

    public function __get($name){
        $isModel = stripos($name, 'Model');
        $isService = stripos($name, 'Service');
        if($isModel !== false){
            return $this->sm->get('Api\Model\\' . ucfirst(substr($name, 0, -5)));
        }elseif($isService !== false){
            return $this->sm->get('COM\Service\\' . ucfirst($name));
        }
    }

}
