<?php
namespace COM\Controller;

use Base\ConstDir\Api\ApiError;
use COM\Controller;
use Base\ConstDir\Redis;

class Api extends Controller{
    /**
     * @var string 版本号
     */
    protected $_version;

    /**
     * @var string 控制器名称
     */
    protected $_controllerName;

    /**
     * @var string 动作名称
     */
    protected $_actionName;
    /**
     * @var array 用户信息
     */
    protected $_memberInfo;

    public function preDispatch()
    {
        //var_dump(strtoupper(md5('sign:Token55b0b5a421f79PayType2ClientComment%E5%8F%91%E8%B4%A7%E5%95%A6%E5%95%A6%F0%9F%98%84versionName1.5systemandroidCarModel109021UseRechargeMoney1UnitePayID1507230609413605')));die;
        parent::preDispatch();
        $this->_controllerName = $this->route->getParam('__CONTROLLER__');
        $this->_actionName = $this->route->getParam('action');
        $this->_version = $this->route->getParam('version');
        $signature = strtolower($this->request->getPost('Signature'));
        if(empty($signature) || (getenv('APP_ENV') != 'test' && $signature != $this->genSignature())){
        }
    }
}