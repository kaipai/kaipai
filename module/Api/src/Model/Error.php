<?php
namespace Api\Model;
use COM\Model;
class Error extends Model{
    protected $table = 'Error';

    public function getErrors($page, $limit, $order = ''){
        $select = $this->getSelect();
        $select->join(array('b' => 'MemberInfo'), 'Error.memberID = b.memberID', array('nickName'), 'left');
        if(empty($order)) $order = 'Error.instime desc';
        $select->order($order);
        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $data = $paginator->getCurrentItems()->getArrayCopy();
        $total = $paginator->getTotalItemCount();

        $res = array(
            'data' => $data,
            'total' => $total,
        );

        return $res;
    }
}