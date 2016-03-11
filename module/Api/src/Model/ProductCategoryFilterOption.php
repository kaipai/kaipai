<?php
namespace Api\Model;

use COM\Model;
class ProductCategoryFilterOption extends Model{


    public function getCategoryFilters($where = array()){
        $select = $this->getSelect();
        $select->join(array('b' => 'ProductCategoryFilter'), 'ProductCategoryFilterOption.productCategoryFilterID = b.productCategoryFilterID')
            ->where($where);
        $tmp = $this->selectWith($select)->toArray();
        $categoryFilters = array();
        foreach($tmp as $v){
            $categoryFilters[$v['productCategoryFilterID']]['info'] = $v;
            $categoryFilters[$v['productCategoryFilterID']]['options'][] = $v;
        }

        return $categoryFilters;
    }

}