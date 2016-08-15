<?php
namespace Api\Model;
use COM\Model;
class ProductPropertyValueCopy extends Model{
    protected $table = 'ProductPropertyValueCopy';

    public function getProperties($productID){
        $select = $this->getSelect();
        $select->join(array('b' => 'ProductCategoryProperty'), 'ProductPropertyValueCopy.productCategoryPropertyID = b.productCategoryPropertyID', array('propertyName'));
        $select->where(array('ProductPropertyValueCopy.productID' => $productID));
        $properties = $this->selectWith($select)->toArray();
        $properties = array_chunk($properties, 2);

        return $properties;
    }
}