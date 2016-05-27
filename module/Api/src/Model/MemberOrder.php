<?php
namespace Api\Model;

use COM\Model;
class MemberOrder extends Model{

    public function getList($where = array(), $order = null, $offset = null, $limit = null){
        $select = $this->getSelect();
        $select->join(array('b' => 'MemberInfo'), 'MemberOrder.memberID = b.memberID', array('nickName'))
            ->where($where)
            ->limit($limit)
            ->offset($offset);
        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber(ceil($offset / $limit) + 1);
        $paginator->setItemCountPerPage($limit);
        $memberOrders = $paginator->getCurrentItems()->getArrayCopy();
        $memberOrdersCount = $paginator->getTotalItemCount();
        return array('memberOrders' => $memberOrders, 'memberOrdersCount' => $memberOrdersCount);

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