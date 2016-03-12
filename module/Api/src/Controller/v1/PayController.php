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
                    $payDetail = $this->memberPayDetailModel->select(array('unitePayID' => $outTradeNo))->current();
                    if($payDetail['payMoney'] != $this->request->getPost('total_fee') || $payDetail['payStatus'] != BaseConst::PAY_DETAIL_STATUS_NOT_PAID) throw new \Exception('fail');
                    $this->sm->get("COM\Service\PayMod\AliPay")->notify($outTradeNo);
                    $requestUri = $_SERVER['REQUEST_URI'];
                    $data = array(
                        'money' => $request->getPost('total_fee'),
                        'payNotifyInfo' => $requestUri,
                        'postData' => json_encode($request->getPost()),
                        'unitePayID' => $outTradeNo,
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
        $unionPay = new \Unionpay($config[getenv('APP_ENV')]['unionPayConfig']);
        if ($unionPay->Verify($this->request->getPost())) {
            try{
                $paidMoney = $this->request->getPost('txnAmt') / 100;
                $payDetail = $this->memberPayDetailModel->select(array('unitePayID' => $this->request->getPost('orderId')))->current();
                if($payDetail['payMoney'] != $paidMoney || $payDetail['payStatus'] != BaseConst::PAY_DETAIL_STATUS_NOT_PAID) throw new \Exception('fail');
                $this->sm->get('COM\Service\PayMod\UnionPay')->notify($this->request->getPost('orderId'));
                $requestUri = $_SERVER['REQUEST_URI'];
                $data = array(
                    'money' => $paidMoney,
                    'payNotifyInfo' => $requestUri,
                    'postData' => json_encode($this->request->getPost()),
                    'unitePayID' => $this->request->getPost('orderId'),
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

}
