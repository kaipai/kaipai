<?php
namespace Api\Model;

use COM\Model;
class MemberOrder extends Model{

    public function getOrderList($where, $page, $limit){
        $select = $this->getSelect();
        $select->join(array('b' => 'MemberInfo'), 'MemberOrder.memberID = b.memberID', array('nickName', 'mobile', 'qq'))
                ->join(array('c' => 'MemberPayDetail'), 'MemberOrder.unitePayID = c.unitePayID', array('payMoney', 'paidMoney', 'commision', 'productPrice', 'payType'))
                ->join(array('g' => 'MemberPayDetail'), 'MemberOrder.finalUnitePayID = g.unitePayID', array('finalPayMoney' => 'payMoney'), 'left')
                ->join(array('d' => 'Product'), 'MemberOrder.productID = d.productID', array('productName'), 'left')
                ->join(array('f' => 'Customization'), 'MemberOrder.customizationID = f.customizationID', array('title'), 'left')
                ->join(array('e' => 'Store'), 'MemberOrder.storeID = e.storeID', array('storeName', 'storeLogo', 'storeqq'), 'left')
                ->where($where);
        $select->order('MemberOrder.instime DESC');
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
        $select->join(array('b' => 'MemberPayDetail'), 'MemberOrder.unitePayID = b.unitePayID', array('payMoney', 'paidMoney'));
        $select->where($where);

        $res = $this->selectWith($select)->current();

        return $res;
    }

}