<?php
namespace Api\Model;

use COM\Model;
class WithdrawLog extends Model{
    public function getLogs($where, $page, $limit){
        $select = $this->getSelect();
        $select->join(array('b' => 'Store'), 'WithdrawLog.storeID = b.storeID', array('storeName'));
        $select->join(array('c' => 'MemberInfo'), 'WithdrawLog.memberID = c.memberID', array('cardOwnerName', 'cardNum', 'cardMobile', 'cardBank', 'rechargeMoney', 'nickName'));
        $select->where($where);
        $select->order('WithdrawLog.instime desc');
        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $data = $paginator->getCurrentItems()->getArrayCopy();
        $total = $paginator->getTotalItemCount();

        $res = array(
            'data' => $data,
            'total' => $total
        );
        return $res;
    }
}