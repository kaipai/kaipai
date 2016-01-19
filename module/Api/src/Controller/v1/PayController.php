<?php

namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\BaseConst;
use COM\Controller;

class PayController extends Controller
{

    public function aliNotifyAction()
    {
        include LIB . '/COM/Service/PayMod/lib/ali/alipay.php';
        $request = $this->request;
        $SignType = $request->getPost('sign_type');
        $config = $this->sm->get('Config');
        $aliConfig = $config[getenv('APP_ENV')]['aliConfig'];
        if ($SignType == 'MD5') {
            $aliPayment = new \AlipayPayment($aliConfig);
            $tmp = $aliPayment->notifyVerify();
        } else {
            $url = "https://mapi.alipay.com/gateway.do?service=notify_verify&partner=" . $aliConfig['partner'] . "&notify_id=" . $this->request->getPost('notify_id');
            $str = file_get_contents($url);
            if (trim($str) == 'true') {
                $tmp = true;
            }
        }
        if ($tmp) {
            $outTradeNo = $request->getPost("out_trade_no");
            $tradeStatus = $request->getPost("trade_status");
            if ($tradeStatus == 'TRADE_FINISHED' || $tradeStatus == 'TRADE_SUCCESS' || $tradeStatus == 'WAIT_SELLER_SEND_GOODS') {
                try{
                    $payDetail = $this->sm->get('Api\Model\MemberPayDetail')->select(array('UnitePayID' => $outTradeNo))->current();
                    if($payDetail['WaitPayMoney'] != $this->request->getPost('total_fee') || $payDetail['PayStatus'] != BaseConst::PAY_STATUS_WAIT_PAY) throw new \Exception('fail');
                    $this->sm->get("COM\Service\PayMod\AliPay")->notify($outTradeNo);
                    $requestUri = $_SERVER['REQUEST_URI'];
                    $data = array(
                        'Money' => $this->request->getPost('total_fee'),
                        'PayNotifyInfo' => $requestUri,
                        'PostData' => json_encode($this->request->getPost()),
                        'UnitePayID' => $outTradeNo,
                    );
                    $this->sm->get('Api\Model\PayNotifyLog')->insert($data);

                    $this->response->setContent('success');
                }catch (\Exception $e){
                    $this->response->setContent('fail');
                }
            } else {
                $this->response->setContent('fail');
            }
        } else {
            $this->response->setContent('fail');
        }
        return $this->response;
    }

    public function unionNotifyAction()
    {
        include LIB . '/COM/Service/PayMod/lib/Union/unionpay.php';
        $config = $this->sm->get('Config');
        $unionPay = new \Unionpay($config[getenv('APP_ENV')]['unionPayConfig']);
        if ($unionPay->Verify($this->request->getPost())) {
            try{
                $paidMoney = $this->request->getPost('txnAmt') / 100;
                $payDetail = $this->sm->get('Api\Model\MemberPayDetail')->select(array('UnitePayID' => $this->request->getPost('orderId')))->current();
                if($payDetail['WaitPayMoney'] != $paidMoney || $payDetail['PayStatus'] != BaseConst::PAY_STATUS_WAIT_PAY) throw new \Exception('fail');
                $this->sm->get('COM\Service\PayMod\UnionPay')->notify($this->request->getPost('orderId'));
                $requestUri = $_SERVER['REQUEST_URI'];
                $data = array(
                    'Money' => $paidMoney,
                    'PayNotifyInfo' => $requestUri,
                    'PostData' => json_encode($this->request->getPost()),
                    'UnitePayID' => $this->request->getPost('orderId'),
                );
                $this->sm->get('Api\Model\PayNotifyLog')->insert($data);
                $this->response->setContent('success');
            }catch(\Exception $e){
                $this->response->setContent('fail');
            }
        } else {
            $this->response->setContent('fail');
        }

        return $this->response;
    }

    public function merchantNotifyAction()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $isJavaNotify = stripos(strtolower($userAgent), 'java');
        $checkSign = $this->sm->get('COM\Service\PayMod\MerchantPay')->checkSign($_SERVER['QUERY_STRING']);
        if ($checkSign && $this->request->getQuery("Succeed") == 'Y' && $isJavaNotify !== false) {

            $payModel = $this->sm->get("Api\Model\MemberPayDetail");
            $payDetail = $payModel->select(array('UnitePayID' => $this->request->getQuery('MerchantPara')))->current();

            if ($payDetail['PayStatus'] == BaseConst::PAY_STATUS_WAIT_PAY) {
                try{
                    if($payDetail['WaitPayMoney'] != $this->request->getQuery('Amount')) throw new \Exception('fail');
                    $this->sm->get('COM\Service\PayMod\MerchantPay')->notify($this->request->getQuery('MerchantPara'));
                    $requestUri = $_SERVER['REQUEST_URI'];
                    $data = array(
                        'Money' => $this->request->getQuery('Amount'),
                        'PayNotifyInfo' => $requestUri,
                        'PostData' => json_encode($this->request->getPost()),
                        'UnitePayID' => $this->request->getQuery('MerchantPara'),
                    );
                    $this->sm->get('Api\Model\PayNotifyLog')->insert($data);
                    $this->response->setContent('success');
                }catch(\Exception $e){
                    $this->response->setContent('fail');
                }
            }else{
                $this->response->setContent('fail');
            }

        } else {
            $this->response->setContent('fail');
        }

        return $this->response;

    }

    public function aliReturnAction(){
        return $this->_return(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function unionReturnAction(){
        return $this->_return(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function merchantPayAction(){
        $unitePayID = $this->params('unitePayID');
        if(empty($unitePayID)) return $this->_return(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);

        try{
            $form = $this->sm->get('COM\Service\PayMod\MerchantPay')->doPay($unitePayID);
            $this->response->setContent($form);
            return $this->response;
        }catch (\Exception $e){
            return $this->_return($e->getCode(), $e->getMessage());
        }
    }

}
