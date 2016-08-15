<?php
namespace Api\Model;

use COM\Model;
use Zend\Db\Sql\Where;

class StoreCategory extends Model{

    public function delNotExistCategory($names = array(), $storeID){
        if(empty($names)) return false;
        $where = new Where();
        $where->notIn('storeCategoryName', $names);
        $where->equalTo('storeID', $storeID);
        return $this->delete($where);
    }
}