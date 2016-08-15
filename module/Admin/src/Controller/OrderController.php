<?php
namespace Admin\Controller;
use Base\ConstDir\Admin\AdminError;
use Base\ConstDir\Admin\AdminSuccess;
use COM\Controller\Admin;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Where;

class OrderController extends Admin{
    public function indexAction(){

        return $this->view;
    }

    public function listAction(){
        $search = $this->queryData['search'];
        $sort = $this->queryData['sort'];
        $order = $this->queryData['order'];
        $where = new Where();
        $where->and->equalTo('MemberOrder.orderType', 1);
        if(!empty($search)){
            $where->and->nest()->or->like('d.productName', '%' . $search . '%')->or->like('MemberOrder.businessID', '%' . $search . '%')->or->like('b.nickName', '%' . $search . '%')->or->like('e.storeName', '%' . $search . '%');
        }
        if(!empty($sort) && !empty($order)){
            $sortOrder = 'MemberOrder.' . $sort . ' ' . $order;
        }

        $result = $this->memberOrderModel->getOrderList($where, $this->pageNum, $this->limit, $sortOrder);
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
        $search = $this->queryData['search'];
        $sort = $this->queryData['sort'];
        $order = $this->queryData['order'];
        $where = new Where();
        $where->and->equalTo('MemberOrder.orderType', 2);
        if(!empty($search)){
            $where->and->nest()->or->like('b.nickName', '%' . $search . '%')->or->like('b.mobile', '%' . $search . '%')->or->like('MemberOrder.businessID', '%' . $search . '%');;
        }
        if(!empty($sort) && !empty($order)){
            $sortOrder = 'MemberOrder.' . $sort . ' ' . $order;
        }

        $orders = $this->memberOrderModel->getOrderList($where, $this->pageNum, $this->limit, $sortOrder);
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

        if(!empty($haveDelivery) && empty($deliveryNum)) return $this->response(AdminError::COMMON_ERROR, '请填写运单号码');

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

    public function updateAction(){
        $orderID = $this->postData['orderID'];

        $where = array(
            'orderID' => $orderID
        );
        if(isset($this->postData['isComplainPaused'])){
            $orderInfo = $this->memberOrderModel->fetch($where);
            if(!empty($orderInfo['autoConfirmDeliveryDoneTime']) && !empty($orderInfo['complainTime'])){
                $this->postData['autoConfirmDeliveryDoneTime'] = $orderInfo['autoConfirmDeliveryDoneTime'] + (time() - $orderInfo['complainTime']);
            }
        }

        $this->memberOrderModel->update($this->postData, $where);

        return $this->response(AdminSuccess::COMMON_SUCCESS, '保存成功');
    }

    public function closeAction(){
        $orderID = $this->postData['orderID'];
        $where = array(
            'orderID' => $orderID,
        );
        $orderInfo = $this->memberOrderModel->fetch($where);
        if(empty($orderInfo)) return $this->response(AdminError::COMMON_ERROR, '订单不存在');

        try{
            $this->memberOrderModel->beginTransaction();
            $this->memberOrderModel->update(array('orderStatus' => -2), array('orderID' => $orderInfo['orderID']));
            $this->memberInfoModel->update(array('rechargeMoney' => new Expression('rechargeMoney + ' . $orderInfo['paidMoney'])), array('memberID' => $orderInfo['memberID']));
            $this->memberRechargeMoneyLogModel->insert(array('memberID' => $orderInfo['memberID'], 'money' => $orderInfo['paidMoney'], 'source' => '后台操作订单' . $orderInfo['businessID'] . '退款', 'type' => 1));
            $this->memberOrderModel->commit();
            return $this->response(AdminSuccess::COMMON_SUCCESS, '关闭成功');
        }catch (\Exception $e){
            $this->memberOrderModel->rollback();
            return $this->response(AdminError::COMMON_ERROR, '关闭失败');
        }
    }
}