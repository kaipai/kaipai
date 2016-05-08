<?php
namespace Api\Model;

use COM\Model;
class ProductFilterOption extends Model{

    public function getList($where = array(), $order = null, $offset = null, $limit = null){

        $select = $this->getSelect();
        $select->columns(array())
            ->join(array('b' => 'Product'), 'ProductFilterOption.productID = b.productID')
            ->where($where)
            ->offset($offset)
            ->limit($limit);
        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber(ceil($offset / $limit) + 1);
        $paginator->setItemCountPerPage($limit);
        $products = $paginator->getCurrentItems()->getArrayCopy();
        $productsCount = $paginator->getTotalItemCount();

        return array('products' => $products, 'productsCount' => $productsCount);

    }

}