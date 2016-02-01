<?php
namespace Api\Model;

use COM\Model;
class Store extends Model{

    public function getCategories($where = array()){
        $select = $this->getSelect();
        $select->from(array('a' => 'Product'))
            ->columns(array('productCategoryID'))
            ->join(array('b' => 'Store'), 'a.storeID = b.storeID', array('storeName'))
            ->join(array('c' => 'ProductCategory'), 'a.productCategoryID = c.productCategoryID', array('productCategoryName'))
            ->where($where)
            ->group(array('a.productCategoryID'));

        return $this->selectWith($select)->toArray();
    }

}