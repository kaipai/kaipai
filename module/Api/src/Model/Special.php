<?php
namespace Api\Model;

use COM\Model;
class Special extends Model{

    public function fetch($where = array()){
        $select = $this->getSelect();
        $select->from(array('a' => 'Special'))
            ->join(array('b' => 'Store'), 'a.storeID = b.storeID', array('storeName'))
            ->where($where);


        return $this->selectWith($select)->current();
    }

}