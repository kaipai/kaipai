<?php
namespace Api\Model;

use COM\Model;
class AuctionLog extends Model{

    public function getLogs($where){
        $select = $this->getSelect();
        $select->where($where);
        $select->order('auctionLogID Desc');
        $res = $this->selectWith($select)->toArray();
        return $res;
    }

}