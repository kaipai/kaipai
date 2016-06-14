<?php

namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\BaseConst;
use COM\Controller\Front;

class PayController extends Front
{

    public function aliNotifyAction()
    {
        include LIB . '/COM/Service/PayMod/lib/ali/alipay.php';
        $request = $this->request;
        $SignType = $request->getPost('sign_type');
        $config = $this->sm->get('Config');
        $aliConfig = $config['aliConfig'];
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
                    $payDetail = $this->memberOrderModel->getOrderInfo($outTradeNo);
                    if($payDetail['payMoney'] != $this->request->getPost('total_fee') || $payDetail['orderStatus'] != 1) throw new \Exception('fail');
                    $this->sm->get("COM\Service\PayMod\AliPay")->notify($outTradeNo);
                    $requestUri = $_SERVER['REQUEST_URI'];
                    $data = array(
                        'money' => $request->getPost('total_fee'),
                        'payNotifyInfo' => $requestUri,
                        'postData' => json_encode($request->getPost()),
                        'unitePayID' => $outTradeNo,
                        'payType' => 2,
                        'type' => 1,
                    );
                    $this->payNotifyLogModel->insert($data);

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
        $unionPay = new \Unionpay($config['unionPayConfig']);
        if ($unionPay->Verify($this->request->getPost())) {
            try{
                $paidMoney = $this->request->getPost('txnAmt') / 100;
                $payDetail = $this->memberOrderModel->getOrderInfo($this->request->getPost('orderId'));
                if($payDetail['payMoney'] != $paidMoney || $payDetail['orderStatus'] != 1) throw new \Exception('fail');
                $this->sm->get('COM\Service\PayMod\UnionPay')->notify($this->request->getPost('orderId'));
                $requestUri = $_SERVER['REQUEST_URI'];
                $data = array(
                    'money' => $paidMoney,
                    'payNotifyInfo' => $requestUri,
                    'postData' => json_encode($this->request->getPost()),
                    'unitePayID' => $this->request->getPost('orderId'),
                    'payType' => 3,
                    'type' => 1,
                );
                $this->payNotifyLogModel->insert($data);
                $this->response->setContent('success');
            }catch(\Exception $e){
                $this->response->setContent('fail');
            }
        } else {
            $this->response->setContent('fail');
        }

        return $this->response;
    }


    public function aliReturnAction(){
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function unionReturnAction(){
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }


    public function aliProductNotifyAction()
    {
        include LIB . '/COM/Service/PayMod/lib/ali/alipay.php';
        $request = $this->request;
        $SignType = $request->getPost('sign_type');
        $config = $this->sm->get('Config');
        $aliConfig = $config['aliConfig'];
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
                    $payDetail = $this->productModel->select(array('unitePayID' => $outTradeNo))->current();
                    if($this->siteSettings['productMoney'] != $this->request->getPost('total_fee') || $payDetail['isPaid']) throw new \Exception('fail');
                    $this->sm->get("COM\Service\PayMod\AliPay")->productNotify($outTradeNo);
                    $requestUri = $_SERVER['REQUEST_URI'];
                    $data = array(
                        'money' => $request->getPost('total_fee'),
                        'payNotifyInfo' => $requestUri,
                        'postData' => json_encode($request->getPost()),
                        'unitePayID' => $outTradeNo,
                        'payType' => 2,
                        'type' => 2,
                    );
                    $this->payNotifyLogModel->insert($data);

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

    public function unionProductNotifyAction()
    {
        include LIB . '/COM/Service/PayMod/lib/Union/unionpay.php';
        $config = $this->sm->get('Config');
        $unionPay = new \Unionpay($config['unionPayConfig']);
        if ($unionPay->Verify($this->request->getPost())) {
            try{
                $paidMoney = $this->request->getPost('txnAmt') / 100;
                $payDetail = $this->productModel->select(array('unitePayID' => $this->request->getPost('orderId')))->current();
                if($this->siteSettings['productMoney'] != $this->request->getPost('total_fee') || $payDetail['isPaid']) throw new \Exception('fail');
                $this->sm->get('COM\Service\PayMod\UnionPay')->productNotify($this->request->getPost('orderId'));
                $requestUri = $_SERVER['REQUEST_URI'];
                $data = array(
                    'money' => $paidMoney,
                    'payNotifyInfo' => $requestUri,
                    'postData' => json_encode($this->request->getPost()),
                    'unitePayID' => $this->request->getPost('orderId'),
                    'payType' => 3,
                    'type' => 2,
                );
                $this->payNotifyLogModel->insert($data);
                $this->response->setContent('success');
            }catch(\Exception $e){
                $this->response->setContent('fail');
            }
        } else {
            $this->response->setContent('fail');
        }

        return $this->response;
    }


    public function aliProductReturnAction(){
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function unionProductReturnAction(){
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function aliSpecialNotifyAction()
    {
        include LIB . '/COM/Service/PayMod/lib/ali/alipay.php';
        $request = $this->request;
        $SignType = $request->getPost('sign_type');
        $config = $this->sm->get('Config');
        $aliConfig = $config['aliConfig'];
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
                    $payDetail = $this->specialModel->select(array('unitePayID' => $outTradeNo))->current();
                    if($this->siteSettings['specialMoney'] != $this->request->getPost('total_fee') || $payDetail['isPaid']) throw new \Exception('fail');
                    $this->sm->get("COM\Service\PayMod\AliPay")->specialNotify($outTradeNo);
                    $requestUri = $_SERVER['REQUEST_URI'];
                    $data = array(
                        'money' => $request->getPost('total_fee'),
                        'payNotifyInfo' => $requestUri,
                        'postData' => json_encode($request->getPost()),
                        'unitePayID' => $outTradeNo,
                        'payType' => 2,
                        'type' => 3,
                    );
                    $this->payNotifyLogModel->insert($data);

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

    public function unionSpecialNotifyAction()
    {
        include LIB . '/COM/Service/PayMod/lib/Union/unionpay.php';
        $config = $this->sm->get('Config');
        $unionPay = new \Unionpay($config['unionPayConfig']);
        if ($unionPay->Verify($this->request->getPost())) {
            try{
                $paidMoney = $this->request->getPost('txnAmt') / 100;
                $payDetail = $this->specialModel->select(array('unitePayID' => $this->request->getPost('orderId')))->current();
                if($this->siteSettings['specialMoney'] != $this->request->getPost('total_fee') || $payDetail['isPaid']) throw new \Exception('fail');
                $this->sm->get('COM\Service\PayMod\UnionPay')->specialNotify($this->request->getPost('orderId'));
                $requestUri = $_SERVER['REQUEST_URI'];
                $data = array(
                    'money' => $paidMoney,
                    'payNotifyInfo' => $requestUri,
                    'postData' => json_encode($this->request->getPost()),
                    'unitePayID' => $this->request->getPost('orderId'),
                    'payType' => 3,
                    'type' => 3,
                );
                $this->payNotifyLogModel->insert($data);
                $this->response->setContent('success');
            }catch(\Exception $e){
                $this->response->setContent('fail');
            }
        } else {
            $this->response->setContent('fail');
        }

        return $this->response;
    }


    public function aliSpecialReturnAction(){
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function unionSpecialReturnAction(){
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }


    public function aliFinalNotifyAction()
    {
        include LIB . '/COM/Service/PayMod/lib/ali/alipay.php';
        $request = $this->request;
        $SignType = $request->getPost('sign_type');
        $config = $this->sm->get('Config');
        $aliConfig = $config['aliConfig'];
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
                    $payDetail = $this->memberOrderModel->getFinalOrderInfo($outTradeNo);
                    if($payDetail['payMoney'] != $this->request->getPost('total_fee') || $payDetail['orderStatus'] != 1) throw new \Exception('fail');
                    $this->sm->get("COM\Service\PayMod\AliPay")->finalNotify($outTradeNo);
                    $requestUri = $_SERVER['REQUEST_URI'];
                    $data = array(
                        'money' => $request->getPost('total_fee'),
                        'payNotifyInfo' => $requestUri,
                        'postData' => json_encode($request->getPost()),
                        'unitePayID' => $outTradeNo,
                        'payType' => 2,
                        'type' => 4,
                    );
                    $this->payNotifyLogModel->insert($data);

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

    public function unionFinalNotifyAction()
    {
        include LIB . '/COM/Service/PayMod/lib/Union/unionpay.php';
        $config = $this->sm->get('Config');
        $unionPay = new \Unionpay($config['unionPayConfig']);
        if ($unionPay->Verify($this->request->getPost())) {
            try{
                $paidMoney = $this->request->getPost('txnAmt') / 100;
                $payDetail = $this->memberOrderModel->getFinalOrderInfo($this->request->getPost('orderId'));
                if($payDetail['payMoney'] != $paidMoney || $payDetail['orderStatus'] != 1) throw new \Exception('fail');
                $this->sm->get('COM\Service\PayMod\UnionPay')->finalNotify($this->request->getPost('orderId'));
                $requestUri = $_SERVER['REQUEST_URI'];
                $data = array(
                    'money' => $paidMoney,
                    'payNotifyInfo' => $requestUri,
                    'postData' => json_encode($this->request->getPost()),
                    'unitePayID' => $this->request->getPost('orderId'),
                    'payType' => 3,
                    'type' => 4,
                );
                $this->payNotifyLogModel->insert($data);
                $this->response->setContent('success');
            }catch(\Exception $e){
                $this->response->setContent('fail');
            }
        } else {
            $this->response->setContent('fail');
        }

        return $this->response;
    }


    public function aliFinalReturnAction(){
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function unionFinalReturnAction(){
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

}
