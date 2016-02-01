<?php
namespace Api\Model;

use COM\Model;
class MemberOrder extends Model{

    public function getList($where = array(), $offset = 0, $limit = 1){
        $select = $this->getSelect();
        $select->from(array('a' => 'MemberOrder'))
            ->join(array('b' => 'MemberInfo'), 'a.memberID = b.memberID')
            ->where($where)
            ->limit($limit)
            ->offset($offset);
        $paginator = $this->memberOrderModel->paginate($select);
        $paginator->setCurrentPageNumber(ceil($offset / $limit) + 1);
        $paginator->setItemCountPerPage($limit);
        $memberOrders = $paginator->getCurrentItems()->toArray();
        $memberOrdersCount = $paginator->getTotalItemCount();

        return array('memberOrders' => $memberOrders, 'memberOrdersCount' => $memberOrdersCount);

    }

}