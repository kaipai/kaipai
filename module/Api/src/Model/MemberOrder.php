<?php
namespace Api\Model;

use COM\Model;
use Zend\Db\Sql\Expression;

class MemberOrder extends Model{

    public function getOrderList($where, $page, $limit, $order = ''){
        $select = $this->getSelect();
        $select->join(array('b' => 'MemberInfo'), 'MemberOrder.memberID = b.memberID', array('nickName', 'mobile', 'qq'))
                ->join(array('c' => 'MemberPayDetail'), 'MemberOrder.unitePayID = c.unitePayID', array('payMoney', 'paidMoney', 'commision', 'productPrice', 'payType'))
                ->join(array('g' => 'MemberPayDetail'), 'MemberOrder.finalUnitePayID = g.unitePayID', array('finalPayMoney' => 'payMoney'), 'left')
                ->join(array('d' => 'Product'), 'MemberOrder.productID = d.productID', array('productName'), 'left')
                ->join(array('f' => 'Customization'), 'MemberOrder.customizationID = f.customizationID', array('title'), 'left')
                ->join(array('e' => 'Store'), 'MemberOrder.storeID = e.storeID', array('storeName', 'storeLogo', 'storeqq'), 'left')
                ->where($where)
                ->where(array('MemberOrder.isDel' => 0));
        if(empty($order)) $order = 'MemberOrder.instime DESC';
        $select->order($order);
        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $data = $paginator->getCurrentItems()->getArrayCopy();
        $pages = $paginator->getPages();
        $total = $paginator->getTotalItemCount();
        return array('data' => $data, 'pages' => $pages, 'total' => $total);

    }

    public function getOrderDetail($where){
        $select = $this->getSelect();
        $select->join(array('b' => 'MemberInfo'), 'MemberOrder.memberID = b.memberID', array('nickName', 'qq'))
            ->join(array('c' => 'MemberPayDetail'), 'MemberOrder.unitePayID = c.unitePayID', array('payMoney', 'paidMoney', 'commision', 'productPrice', 'deliveryPrice'))
            ->join(array('g' => 'MemberPayDetail'), 'MemberOrder.finalUnitePayID = g.unitePayID', array('finalPayMoney' => 'payMoney'), 'left')
            ->join(array('d' => 'Product'), 'MemberOrder.productID = d.productID', array('productName'), 'left')
            ->join(array('f' => 'Customization'), 'MemberOrder.customizationID = f.customizationID', array('title'), 'left')
            ->join(array('e' => 'Store'), 'MemberOrder.storeID = e.storeID', array('storeName', 'storeLogo', 'storeqq'), 'left')
            ->join(array('h' => 'MemberOrderDelivery'), 'MemberOrder.orderID = h.orderID', array('deliveryNum'), 'left')
            ->join(array('i' => 'DeliveryType'), 'h.deliveryTypeID = i.deliveryTypeID', array('typeName'), 'left')
            ->join(array('j' => 'MemberDelivery'), 'h.memberDeliveryID = j.memberDeliveryID', array('receiverName', 'receiverMobile', 'receiverAddr'), 'left')
            ->where($where);
        $result = $this->selectWith($select)->current();
        return $result;

    }

    public function getOrders($where){
        $select = $this->getSelect();
        $select->join(array('b' => 'MemberInfo'), 'MemberOrder.memberID = b.memberID', array('nickName'))
            ->join(array('c' => 'MemberPayDetail'), 'MemberOrder.unitePayID = c.unitePayID', array('payMoney'))
            ->where($where);
        $select->order('MemberOrder.instime asc');
        $orders = $this->selectWith($select)->toArray();
        return $orders;
    }

    public function genBusinessID(){
        return date('ymdhis') . substr(microtime(), 2, 3) . rand(0, 9);
    }

    public function genUnitePayID(){
        return date('ymdhis') . substr(microtime(), 2, 3) . rand(0, 9);
    }

    public function getOrderInfo($unitePayID){
        $select = $this->getSelect();
        $select->join(array('b' => 'MemberPayDetail'), 'MemberOrder.unitePayID = b.unitePayID', array('payMoney'));
        $select->where(array('MemberOrder.unitePayID' => $unitePayID));

        $res = $this->selectWith($select)->current();

        return $res;
    }

    public function getFinalOrderInfo($unitePayID){
        $select = $this->getSelect();
        $select->join(array('b' => 'MemberPayDetail'), 'MemberOrder.finalUnitePayID = b.unitePayID', array('payMoney'));
        $select->where(array('MemberOrder.finalUnitePayID' => $unitePayID));

        $res = $this->selectWith($select)->current();

        return $res;
    }

    public function fetch($where = array()){
        $select = $this->getSelect();
        $select->join(array('b' => 'MemberPayDetail'), 'MemberOrder.unitePayID = b.unitePayID', array('payMoney', 'paidMoney', 'productPrice'));
        $select->where($where);

        $res = $this->selectWith($select)->current();

        return $res;
    }

    public function confirmDeliveryDone($orderInfo){
        try{
            $this->beginTransaction();
            $this->update(array('orderStatus' => 5), array('orderID' => $orderInfo['orderID']));
            if(!empty($orderInfo['storeID'])){
                $storeInfo = $this->storeModel->fetch(array('storeID' => $orderInfo['storeID']));
                if(!empty($storeInfo['fees'])){
                    $siteFees = $orderInfo['productPrice'] * $storeInfo['fees'];
                    $this->memberPayDetailModel->update(array('siteFees' => $siteFees), array('unitePayID' => $orderInfo['unitePayID']));
                    $orderInfo['paidMoney'] -= $siteFees;

                }
                $this->memberInfoModel->update(array('rechargeMoney' => new Expression('rechargeMoney + ' . $orderInfo['paidMoney'])), array('storeID' => $orderInfo['storeID']));
                $this->memberRechargeMoneyLogModel->insert(array('memberID' => $storeInfo['memberID'], 'money' => $orderInfo['paidMoney'], 'unitePayID' => $orderInfo['unitePayID'], 'source' => '订单确认收货打款'));
                if(!empty($siteFees)){
                    $this->memberRechargeMoneyLogModel->insert(array('memberID' => $storeInfo['memberID'], 'money' => $siteFees, 'unitePayID' => $orderInfo['unitePayID'], 'source' => '网站佣金', 'type' => 2));
                }
            }

            $this->commit();

        }catch (\Exception $e){
            $this->rollback();
        }

        return true;
    }

}