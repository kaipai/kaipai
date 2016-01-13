<?php
namespace COM\Controller;

use Base\ConstDir\Api\ApiError;
use COM\Controller;
use Base\ConstDir\Redis;

class Api extends Controller{
    /**
     * @var string 版本号
     */
    protected $version;
    /**
     * @var string 控制器名称
     */
    protected $controllerName;
    /**
     * @var string 动作名称
     */
    protected $actionName;
    /**
     * @var array 用户信息
     */
    protected $memberInfo;
    /**
     * @var int 分页起始值
     */
    protected $offset;
    /**
     * @var int 分页条数
     */
    protected $limit;

    public function preDispatch()
    {
        parent::preDispatch();
        $this->controllerName = $this->route->getParam('__CONTROLLER__');
        $this->actionName = $this->route->getParam('action');
        $this->version = $this->route->getParam('version');
        $signature = strtolower($this->request->getPost('Signature'));
        if(empty($signature) || (getenv('APP_ENV') != 'test' && $signature != $this->genSignature())){
        }
    }
}