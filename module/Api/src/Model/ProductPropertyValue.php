<?php
namespace Api\Model;

use COM\Model;
class ProductPropertyValue extends Model{

    public function getProperties($productID){
        $select = $this->getSelect();
        $select->join(array('b' => 'ProductCategoryProperty'), 'ProductPropertyValue.productCategoryPropertyID = b.productCategoryPropertyID', array('propertyName'));
        $select->where(array('ProductPropertyValue.productID' => $productID));
        $properties = $this->selectWith($select)->toArray();
        $properties = array_chunk($properties, 2);

        return $properties;
    }
}