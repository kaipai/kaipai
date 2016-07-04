<?php
namespace Admin\Controller;
use Base\ConstDir\Admin\AdminError;
use Base\ConstDir\Admin\AdminSuccess;
use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Admin;

class OrderController extends Admin{
    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $where = array('MemberOrder.orderType' => 1);
        $result = $this->memberOrderModel->getOrderList($where, $this->pageNum, $this->limit);
        $orders = $result['data'];
        foreach($orders as $k => $v){
            if(!empty($v['productSnapshot'])){
                $tmp = json_decode($v['productSnapshot'], true);
                $orders[$k]['productName'] = $tmp['productName'];
            }elseif(!empty($v['customizationSnapshot'])){
                $tmp = json_decode($v['customizationSnapshot'], true);
                $orders[$k]['productName'] = $tmp['title'];
            }
        }

        return $this->adminResponse(array('rows' => $orders, 'total' => $result['total']));
    }

    public function customizationAction(){

        $deliveryTypes = $this->deliveryTypeModel->select()->toArray();
        $this->view->setVariables(array(
            'deliveryTypes' => $deliveryTypes
        ));
        return $this->view;
    }

    public function customizationListAction(){
        $where = array(
            'MemberOrder.orderType' => 2,
        );
        $orders = $this->memberOrderModel->getOrderList($where, $this->pageNum, $this->limit);
        foreach($orders['data'] as $k => $v){
            $tmp = json_decode($v['customizationSnapshot'], true);
            $orders['data'][$k]['title'] = $tmp['title'];
            $orders['data'][$k]['price'] = $tmp['price'];
            $orders['data'][$k]['depositPrice'] = $tmp['depositPrice'];
            $orders['data'][$k]['listImg'] = $tmp['listImg'];
            $delivery = $this->memberOrderDeliveryModel->getOrderDelivery(array('MemberOrderDelivery.orderID' => $v['orderID']));
            $orders['data'][$k]['delivery'] = array(
                'receiverName' => $delivery['receiverName'],
                'receiverMobile' => $delivery['receiverMobile'],
                'receiverAddr' => $delivery['receiverAddr']
            );
            $orders['data'][$k]['receiverMobile'] = $delivery['receiverMobile'];
            $orders['data'][$k]['receiverAddr'] = $delivery['receiverAddr'];
        }

        return $this->adminResponse(array('rows' => $orders['data'], 'total' => $orders['total']));
    }

    public function setDeliveryAction(){
        $orderID = $this->queryData['orderID'];
        $haveDelivery = $this->queryData['haveDelivery'];
        $deliveryTypeID = $this->queryData['deliveryTypeID'];
        $deliveryNum = $this->queryData['deliveryNum'];

        if(!empty($haveDelivery) && empty($deliveryNum)) return $this->response(ApiError::COMMON_ERROR, '请填写运单号码');

        try{
            $this->memberOrderDeliveryModel->beginTransaction();
            $this->memberOrderDeliveryModel->update(array('deliveryTypeID' => $deliveryTypeID, 'deliveryNum' => $deliveryNum), array('orderID' => $orderID));
            $this->memberOrderModel->update(array('orderStatus' => 5), array('orderID' => $orderID));
            $this->memberOrderDeliveryModel->commit();
            return $this->response(AdminSuccess::COMMON_SUCCESS, '发货成功');
        }catch (\Exception $e){

            $this->memberOrderDeliveryModel->rollback();
            return $this->response(AdminError::COMMON_ERROR, '发货失败');
        }
    }

    public function confirmPaidAction(){
        $unitePayID = $this->postData['unitePayID'];
        $type = $this->postData['type'];

        try{
            if($type == 1){
                $orderInfo = $this->memberOrderModel->select(array('unitePayID' => $unitePayID))->current();
                if(empty($orderInfo)) return $this->response(AdminError::COMMON_ERROR, '订单信息不存在');
                $this->sm->get('COM\Service\PayMod\RechargePay')->notify($unitePayID);
            }elseif($type == 2){
                $productInfo = $this->productModel->select(array('unitePayID' => $unitePayID))->current();
                if(empty($productInfo)) return $this->response(AdminError::COMMON_ERROR, '商品信息不存在');
                $this->sm->get('COM\Service\PayMod\RechargePay')->productNotify($unitePayID);
            }elseif($type == 3){
                $specialInfo = $this->specialModel->select(array('unitePayID' => $unitePayID))->current();
                if(empty($specialInfo)) return $this->response(AdminError::COMMON_ERROR, '专场信息不存在');
                $this->sm->get('COM\Service\PayMod\RechargePay')->specialNotify($unitePayID);
            }elseif($type == 4){
                $orderInfo = $this->memberOrderModel->select(array('unitePayID' => $unitePayID))->current();
                if(empty($orderInfo)) return $this->response(AdminError::COMMON_ERROR, '订单信息不存在');
                $this->sm->get('COM\Service\PayMod\RechargePay')->finalNotify($orderInfo['finalUnitePayID']);
            }
            return $this->response(AdminSuccess::COMMON_SUCCESS, '确认成功');
        }catch (\Exception $e){
            return $this->response(AdminError::COMMON_ERROR, '事务处理失败');
        }
    }
}