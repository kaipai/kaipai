<?php
namespace Api\Model;

use COM\Model;
class MemberRechargeMoneyLog extends Model{

    public function getLogs($where, $page = 1, $limit = 10){
        $select = $this->getSelect();
        $select->where($where);
        $select->order('instime desc');
        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $data = $paginator->getCurrentItems()->getArrayCopy();
        $total = $paginator->getTotalItemCount();
        $pages = $paginator->getPages();
        $res = array(
            'data' => $data,
            'total' => $total,
            'pages' => $pages,
        );
        return $res;
    }
}