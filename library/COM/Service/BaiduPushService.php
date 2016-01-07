<?php
namespace COM\Service;

use Base\Functions\Utility;
use Zend\Code\Scanner\Util;

class BaiduPushService
{
    /** @var  \PushSDK */
    protected $pushSdkAnd;
    /** @var  \PushSDK */
    protected $pushSdkIos;

    protected $opts = array(
        'msg_type' => 1,
    );

    protected $info = array();
    /**
     * 全推
     * @param $title
     * @param $content
     * @param array $errAnd
     * @param array $errIos
     * @return bool
     */
//    public function sendAll($title, $content, array &$err= array()){

    //andriod push start
//        $ars = $this->pushSdkAnd->pushMsgToAll(array('title'=>$title, 'description'=>$content, $this->opts));
//        if($ars === false) {
//            $err['code'] = $this->pushSdkAnd->getLastErrorCode();
//            $err['code'] = $this->pushSdkAnd->getLastErrorMsg();
//        }
    //andriod push end

//        //ios push start
//        $irs = false;
//        $irs = $this->pushSdkIos->pushMsgToAll(array('aps'=>array("alert"=>$content)), $this->opts);
//        if($irs === false) {
//            $err['code'] = $this->pushSdkAnd->getLastErrorCode();
//            $err['code'] = $this->pushSdkAnd->getLastErrorMsg();
//        }
//        //ios push end

//        return $ars || $irs;
//    }

    /**
     * 单挑测试
     * @param $channlId
     * @param null $title
     * @param $content
     * @param array $errAnd
     * @param array $errIos
     * @return bool
     */
    public function sendSingle($channlId, $title = null, $content, array &$errAnd = array(), array &$errIos = array())
    {
        //andriod push start
        $sendAndInfo = array('title' => $title, 'description' => $content, "custom_content" => $this->getInfo());
        $ars = $this->pushSdkAnd->pushMsgToSingleDevice($channlId, $sendAndInfo, $this->opts);
//        if($ars === false) {
        $errAnd['code'] = $this->pushSdkAnd->getLastErrorCode();
        $errAnd['msg'] = $this->pushSdkAnd->getLastErrorMsg();
//        }
        //andriod push end

        //ios push start
        $sendIosInfo = array_merge(array('aps' => array("alert" => $content)), $this->getInfo());

        $irs = $this->pushSdkIos->pushMsgToSingleDevice($channlId, $sendIosInfo, $this->opts);
//        if($irs === false) {
        $errIos['code'] = $this->pushSdkAnd->getLastErrorCode();
        $errIos['msg'] = $this->pushSdkAnd->getLastErrorMsg();
//        }
        return $ars || $irs;
        //ios push end
    }

    public function SendBatchUniMsg(array $channlIds, $title = null, $content, array &$err = array())
    {
        //andriod push start
        $sendAndInfo = array('title' => $title, 'description' => $content, "custom_content" => $this->getInfo());
        $ars = $this->pushSdkAnd->pushBatchUniMsg($channlIds, $sendAndInfo, $this->opts);
//        if($ars === false) {
        $err['and']['code'] = $this->pushSdkAnd->getLastErrorCode();
        $err['and']['msg'] = $this->pushSdkAnd->getLastErrorMsg();
//        }
        //andriod push end

        //ios push start
        $sendIosInfo = array_merge(array('aps' => array("alert" => $content)), $this->getInfo());
        foreach ($channlIds as $channlId) {
            $irs = $this->pushSdkIos->pushMsgToSingleDevice($channlId, $sendIosInfo, $this->opts);
//            if($irs === false) {
            $err['ios']['code'] = $this->pushSdkIos->getLastErrorCode();
            $err['ios']['msg'] = $this->pushSdkIos->getLastErrorMsg();
//            }
        }
        //ToDo 批量推送有问题
//        $irs = $this->pushSdkIos->pushBatchUniMsg($channlIds, array('aps'=>array("alert"=>$content)),array('opt'=>array('deploy_status'=>1)));
//        if($irs === false) {
//            $err['code'] = $this->pushSdkIos->getLastErrorCode();
//            $err['msg'] = $this->pushSdkIos->getLastErrorMsg();
//        }
        return $ars || $irs;
//        ios push end
    }


    public function setConfig($sm)
    {
        $bdConfig = $sm->get('Config')[getenv('APP_ENV')]['bd-config'];
        $this->pushSdkIos = new \PushSDK($bdConfig['ios']['ak'], $bdConfig['ios']['sk']);
        $this->pushSdkIos->setDeviceType(4);
        $this->opts['deploy_status'] = (getenv('APP_ENV') == 'test' || getenv('APP_ENV') == 'staging') ? 1 : 2;
        $this->pushSdkAnd = new \PushSDK($bdConfig['and']['ak'], $bdConfig['and']['sk']);

    }

    public function setOpt($pkgContent = '')
    {
        if (!empty($pkgContent)) {
            $this->opts['open_type'] = 2;
            $this->opts['pkg_content'] = $pkgContent;
        }
        return $this;
    }


    /**
     * @param array $info
     * $info = array('type'=>?, 'value'=>?)
     * 1. 服务激活通知—APP首页；
     * 2. 受理状态变更—受理详情页面；
     * 3. 活动通知—H5页面（地址需要由运营在后台配置)
     */
    public function setInfo($info = array())
    {
        if (!empty($info))
            $this->info = $info;
    }

    public function getInfo()
    {
        return $this->info;
    }
}