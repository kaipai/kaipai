<?php
namespace Api\Model;

use COM\Model;
class ProductCategoryFilter extends Model{

    public function getList($where = array()){
        $select = $this->getSelect();
        $select->from(array('a' => 'ProductCategoryFilterOption'))
            ->join(array('b' => 'ProductCategoryFilter'), 'a.productCategoryFilterID = b.productCategoryFilterID')
            ->where($where);
        $tmp = $this->productCategoryFilterOptionModel->selectWith($select)->toArray();
        $categoryFilters = array();
        foreach($tmp as $v){
            $categoryFilters[$v['productCategoryFilterID']]['info'] = $v;
            $categoryFilters[$v['productCategoryFilterID']]['options'][] = $v;
        }

        return $categoryFilters;

    }

}