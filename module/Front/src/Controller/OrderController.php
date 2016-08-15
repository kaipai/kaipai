<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Front;
use Zend\Db\Sql\Predicate\Expression;

class OrderController extends Front{

    public function init(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
    }

    public function generateAction(){
        $customizationID = $this->postData['customizationID'];
        $customizationCount = intval($this->postData['customizationCount']);
        if(empty($customizationID) || empty($customizationCount)) return $this->response(ApiError::COMMON_ERROR, '参数错误');
        $customizationInfo = $this->customizationModel->select(array('customizationID' => $customizationID))->current();
        if(!empty($customizationInfo)){
            $orderData['customizationSnapshot'] = json_encode($customizationInfo);
        }else{
            return $this->response(ApiError::COMMON_ERROR, '定制信息不存在');
        }
        $productID = $this->postData['productID'];
        $businessID = $this->memberOrderModel->genBusinessID();
        $unitePayID = $this->memberOrderModel->genUnitePayID();
        $orderData = array(
            'businessID' => $businessID,
            'unitePayID' => $unitePayID,
            'memberID' => $this->memberInfo['memberID'],
            'productID' => $productID,
            'customizationID' => $customizationID,
            'customizationCount' => $customizationCount,
            'orderType' => 2,
            'customizationSnapshot' => json_encode($customizationInfo),
        );

        if($customizationInfo['lastNum'] < 1) return $this->response(ApiError::COMMON_ERROR, '定制数量已抢光');
        $payMoney = $customizationCount * $customizationInfo['depositPrice'];

        try{
            $this->memberOrderModel->beginTransaction();
            $this->memberOrderModel->insert($orderData);
            $orderID = $this->memberOrderModel->getLastInsertValue();
            $payData = array(
                'unitePayID' => $unitePayID,
                'payMoney' => $payMoney,
                'productPrice' => $payMoney,
            );
            $this->memberPayDetailModel->insert($payData);
            $finalUnitePayID = $this->memberOrderModel->genUnitePayID();
            $finalPrice = ($customizationInfo['price'] * $customizationCount) - $payMoney;
            $finalPayData = array(
                'unitePayID' => $finalUnitePayID,
                'payMoney' => $finalPrice,
                'productPrice' => $payMoney,
            );
            $this->memberOrderModel->update(array('finalUnitePayID' => $finalUnitePayID, 'finalPrice' => $finalPrice), array('orderID' => $orderID));
            $this->memberPayDetailModel->insert($finalPayData);

            $this->memberOrderModel->commit();

            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('orderID' => $orderID));
        }catch (\Exception $e){
            $this->memberOrderModel->rollback();

            return $this->response($e->getCode(), $e->getMessage());
        }



    }


    public function payAction(){
        $orderID = $this->postData['orderID'];
        $memberDeliveryID = $this->postData['memberDeliveryID'];
        $payType = $this->postData['payType'];
        $orderRemark = $this->postData['orderRemark'];
        $orderInfo = $this->memberOrderModel->select(array('orderID' => $orderID, 'memberID' => $this->memberInfo['memberID']))->current();
        if(empty($orderInfo)) return $this->response(ApiError::COMMON_ERROR, '订单信息不存在');
        if($orderInfo['orderType'] == 1){
            $tmp = json_decode($orderInfo['productSnapshot'], true);
            $productName = $tmp['productName'];
        }elseif($orderInfo['orderType'] == 2){
            $tmp = json_decode($orderInfo['customizationSnapshot'], true);
            $productName = $tmp['title'];
        }
        try{
            $this->memberOrderModel->beginTransaction();

            $this->memberOrderModel->update(array('remark' => $orderRemark), array('orderID' => $orderID));
            $this->memberPayDetailModel->update(array('payType' => $payType), array('unitePayID' => $orderInfo['unitePayID']));
            $orderDelivery = $this->memberOrderDeliveryModel->select(array('orderID' => $orderID))->current();
            if(!empty($orderDelivery)){
                $this->memberOrderDeliveryModel->update(array('memberDeliveryID' => $memberDeliveryID), array('orderID' => $orderID));
            }else{
                $orderDelivery = array(
                    'orderID' => $orderID,
                    'memberID' => $this->memberInfo['memberID'],
                    'memberDeliveryID' => $memberDeliveryID,
                );
                $this->memberOrderDeliveryModel->insert($orderDelivery);
            }


            $this->memberOrderModel->commit();

            $payDetail = $this->memberPayDetailModel->select(array('unitePayID' => $orderInfo['unitePayID']))->current();
            $unitePayID = $payDetail['unitePayID'];
            $price = $payDetail['payMoney'];
            if($payType == 1){
                if($this->memberInfo['isRechargeMoneyLocked']) return $this->response(ApiError::COMMON_ERROR, ApiError::RECHARGE_MONEY_LOCKED);
                if($price > $this->memberInfo['rechargeMoney']){
                    return $this->response(ApiError::COMMON_ERROR, '余额不足以支付');
                }else{
                    try{
                        $this->sm->get('COM\Service\PayMod\RechargePay')->notify($unitePayID, $price);
                        return $this->response($payType, '支付成功');
                    }catch (\Exception $e){
                        return $this->response($e->getCode(), $e->getMessage());
                    }

                }
            }if($payType == 2){
                $payUrl = $this->sm->get('COM\Service\PayMod\AliPay')->doPay($unitePayID, $price, $productName);
                return $this->response($payType, ApiSuccess::COMMON_SUCCESS_MSG, array('coreData' => $payUrl));
            }elseif($payType == 3){
                $payForm = $this->sm->get('COM\Service\PayMod\UnionPay')->doPay($unitePayID, $price, $productName);
                return $this->response($payType, ApiSuccess::COMMON_SUCCESS_MSG, array('coreData' => $payForm));
            }elseif($payType == 4){
                $this->memberPayDetailModel->update(array('payType' => $payType), array('unitePayID' => $unitePayID));

                return $this->response($payType, ApiSuccess::COMMON_SUCCESS_MSG);
            }elseif($payType == 5){
                $payPic = $this->sm->get('COM\Service\PayMod\WxPay')->doPay($unitePayID, $price, $productName);
                return $this->response($payType, ApiSuccess::COMMON_SUCCESS_MSG, array('coreData' => $payPic));
            }else{
                return $this->response(ApiError::COMMON_ERROR, '请选择支付方式');
            }

        }catch (\Exception $e){
            //$this->memberOrderModel->rollback();

            return $this->response($e->getCode(), $e->getMessage());
        }
    }

    public function payProductAction(){
        $unitePayID = $this->postData['unitePayID'];
        $payType = $this->postData['payType'];
        $productInfo = $this->productModel->select(array('unitePayID' => $unitePayID))->current();
        if(!empty($productInfo)){
            $price = $this->siteSettings['productMoney'];
            $this->productModel->update(array('payType' => $payType), array('unitePayID' => $unitePayID));
            if($payType == 1){
                if($this->memberInfo['isRechargeMoneyLocked']) return $this->response(ApiError::COMMON_ERROR, ApiError::RECHARGE_MONEY_LOCKED);
                if($price > $this->memberInfo['rechargeMoney']){
                    return $this->response(ApiError::COMMON_ERROR, '余额不足以支付');
                }else{
                    try{
                        $this->sm->get('COM\Service\PayMod\RechargePay')->productNotify($unitePayID, $price);
                        return $this->response($payType, '支付成功');
                    }catch (\Exception $e){
                        return $this->response($e->getCode(), $e->getMessage());
                    }
                }
            }if($payType == 2){
                $payUrl = $this->sm->get('COM\Service\PayMod\AliPay')->productDoPay($unitePayID, $price);
                return $this->response($payType, ApiSuccess::COMMON_SUCCESS_MSG, array('coreData' => $payUrl));
            }elseif($payType == 3){
                $payForm = $this->sm->get('COM\Service\PayMod\UnionPay')->productDoPay($unitePayID, $price);
                return $this->response($payType, ApiSuccess::COMMON_SUCCESS_MSG, array('coreData' => $payForm));
            }elseif($payType == 4){
                return $this->response($payType, ApiSuccess::COMMON_SUCCESS_MSG);
            }elseif($payType == 5){
                $payPic = $this->sm->get('COM\Service\PayMod\WxPay')->productDoPay($unitePayID, $price);
                return $this->response($payType, ApiSuccess::COMMON_SUCCESS_MSG, array('coreData' => $payPic));
            }else{
                return $this->response(ApiError::COMMON_ERROR, '请选择支付方式');
            }
        }else{
            return $this->response(ApiError::COMMON_ERROR, '支付号错误');
        }
    }

    public function paySpecialAction(){
        $unitePayID = $this->postData['unitePayID'];
        $payType = $this->postData['payType'];
        $specialInfo = $this->specialModel->select(array('unitePayID' => $unitePayID))->current();
        if(!empty($specialInfo)){
            $price = $this->siteSettings['specialMoney'];
            $this->specialModel->update(array('payType' => $payType), array('unitePayID' => $unitePayID));
            if($payType == 1){
                if($this->memberInfo['isRechargeMoneyLocked']) return $this->response(ApiError::COMMON_ERROR, ApiError::RECHARGE_MONEY_LOCKED);
                if($price > $this->memberInfo['rechargeMoney']){
                    return $this->response(ApiError::COMMON_ERROR, '余额不足以支付');
                }else{
                    try{
                        $this->sm->get('COM\Service\PayMod\RechargePay')->specialNotify($unitePayID, $price);
                        return $this->response($payType, '支付成功');
                    }catch (\Exception $e){
                        return $this->response($e->getCode(), $e->getMessage());
                    }
                }
            }if($payType == 2){
                $payUrl = $this->sm->get('COM\Service\PayMod\AliPay')->specialDoPay($unitePayID, $price);
                return $this->response($payType, ApiSuccess::COMMON_SUCCESS_MSG, array('coreData' => $payUrl));
            }elseif($payType == 3){
                $payForm = $this->sm->get('COM\Service\PayMod\UnionPay')->specialDoPay($unitePayID, $price);
                return $this->response($payType, ApiSuccess::COMMON_SUCCESS_MSG, array('coreData' => $payForm));
            }elseif($payType == 4){
                return $this->response($payType, ApiSuccess::COMMON_SUCCESS_MSG);
            }elseif($payType == 5){
                $payPic = $this->sm->get('COM\Service\PayMod\WxPay')->specialDoPay($unitePayID, $price);
                return $this->response($payType, ApiSuccess::COMMON_SUCCESS_MSG, array('coreData' => $payPic));
            }else{
                return $this->response(ApiError::COMMON_ERROR, '请选择支付方式');
            }
        }else{
            return $this->response(ApiError::COMMON_ERROR, '支付号错误');
        }
    }

    /*public function payFinalAction(){
        $unitePayID = $this->postData['unitePayID'];
        $payType = $this->postData['payType'];
        $payDetail = $this->memberPayDetailModel->select(array('unitePayID' => $unitePayID))->current();
        if(!empty($payDetail)){
            $price = $payDetail['payMoney'];
            if($payType == 1){
                if($price > $this->memberInfo['rechargeMoney']){
                    return $this->response(ApiError::COMMON_ERROR, '余额不足以支付');
                }else{
                    try{
                        $this->sm->get('COM\Service\PayMod\RechargePay')->finalNotify($unitePayID, $price);
                        return $this->response($payType, '支付成功');
                    }catch (\Exception $e){
                        return $this->response($e->getCode(), $e->getMessage());
                    }
                }
            }if($payType == 2){
                $payUrl = $this->sm->get('COM\Service\PayMod\AliPay')->finalDoPay($unitePayID, $price);
                return $this->response($payType, ApiSuccess::COMMON_SUCCESS_MSG, array('coreData' => $payUrl));
            }elseif($payType == 3){
                $payForm = $this->sm->get('COM\Service\PayMod\UnionPay')->finalDoPay($unitePayID, $price);
                return $this->response($payType, ApiSuccess::COMMON_SUCCESS_MSG, array('coreData' => $payForm));
            }elseif($payType == 4){
                $this->memberPayDetailModel->update(array('payType' => $payType), array('unitePayID' => $unitePayID));

                return $this->response($payType, ApiSuccess::COMMON_SUCCESS_MSG);
            }else{
                return $this->response(ApiError::COMMON_ERROR, '请选择支付方式');
            }
        }else{
            return $this->response(ApiError::COMMON_ERROR, '支付号错误');
        }
    }*/
}