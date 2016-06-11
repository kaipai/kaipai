<?php
namespace Api\Model;

use COM\Model;
class MemberOrder extends Model{

    public function getOrderList($where, $page, $limit){
        $select = $this->getSelect();
        $select->join(array('b' => 'MemberInfo'), 'MemberOrder.memberID = b.memberID', array('nickName'))
                ->join(array('c' => 'MemberPayDetail'), 'MemberOrder.unitePayID = c.unitePayID', array('payMoney', 'paidMoney', 'commision', 'productPrice'))
                ->join(array('d' => 'Product'), 'MemberOrder.productID = d.productID', array('productName'), 'left')
                ->join(array('e' => 'Store'), 'MemberOrder.storeID = e.storeID', array('storeName'), 'left')
                ->where($where);
        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $data = $paginator->getCurrentItems()->getArrayCopy();
        $pages = $paginator->getPages();
        return array('data' => $data, 'pages' => $pages);

    }

    public function getOrderDetail($where){
        $select = $this->getSelect();
        $select->join(array('b' => 'MemberInfo'), 'MemberOrder.memberID = b.memberID', array('nickName'))
            ->join(array('c' => 'MemberPayDetail'), 'MemberOrder.unitePayID = c.unitePayID', array('payMoney', 'paidMoney', 'commision', 'productPrice'))
            ->join(array('d' => 'Product'), 'MemberOrder.productID = d.productID', array('productName'), 'left')
            ->join(array('e' => 'Store'), 'MemberOrder.storeID = e.storeID', array('storeName'), 'left')
            ->where($where);
        $result = $this->selectWith($select)->current();
        return $result;

    }

    public function getOrders($where){
        $select = $this->getSelect();
        $select->join(array('b' => 'MemberInfo'), 'MemberOrder.memberID = b.memberID', array('nickName'))
            ->join(array('c' => 'MemberPayDetail'), 'MemberOrder.unitePayID = c.unitePayID', array('payMoney'))
            ->where($where);

        $orders = $this->selectWith($select)->toArray();
        return $orders;
    }

    public function genBusinessID(){
        return date('ymdhis') . substr(microtime(), 2, 3) . rand(0, 9);
    }

    public function genUnitePayID(){
        return date('ymdhis') . substr(microtime(), 2, 3) . rand(0, 9);
    }

}