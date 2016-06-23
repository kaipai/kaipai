<?php
namespace Api\Model;

use COM\Model;
class MemberOrderDelivery extends Model{

    public function getOrderDelivery($where){
        $select = $this->getSelect();
        $select->join(array('b' => 'MemberDelivery'), 'MemberOrderDelivery.memberDeliveryID = b.memberDeliveryID', array('receiverName', 'receiverMobile', 'receiverAddr'));
        $select->join(array('c' => 'DeliveryType'), 'MemberOrderDelivery.deliveryTypeID = c.deliveryTypeID', array('typeName'));
        $select->where($where);
        $res = $this->selectWith($select)->current();

        return $res;
    }
}