<?php
namespace Api\Model;

use COM\Model;
class ProductCategoryFilterOption extends Model{


    public function getCategoryFilters($where = array()){
        $select = $this->getSelect();
        $select->join(array('b' => 'ProductCategoryFilter'), 'ProductCategoryFilterOption.productCategoryFilterID = b.productCategoryFilterID', array('filterName', 'productCategoryFilterID'))
            ->where($where);
        $tmp = $this->selectWith($select)->toArray();
        $categoryFilters = array();
        foreach($tmp as $v){
            $categoryFilters[$v['productCategoryFilterID']]['filterName'] = $v['filterName'];
            $categoryFilters[$v['productCategoryFilterID']]['productCategoryFilterID'] = $v['productCategoryFilterID'];
            $categoryFilters[$v['productCategoryFilterID']]['options'][] = array(
                'optionID' => $v['productCategoryFilterOptionID'],
                'optionName' => $v['optionName'],
            );
        }
        $result = array_values($categoryFilters);

        return $result;
    }

}