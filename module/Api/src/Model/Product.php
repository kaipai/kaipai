<?php
namespace Api\Model;

use COM\Model;
class Product extends Model{

    public function getList($where = array(), $offset = 0, $limit = 1){
        $select = $this->getSelect();
        $select->from(array('a' => 'productFilterOption'))
            ->join(array('b' => 'product'), 'a.productID = b.productID')
            ->where($where)
            ->limit($limit)
            ->offset($offset);

        $paginator = $this->productModel->paginate($select);
        $paginator->setCurrentPageNumber(ceil($offset / $limit) + 1);
        $paginator->setItemCountPerPage($limit);
        $products = $paginator->getCurrentItems()->toArray();
        $productsCount = $paginator->getTotalItemCount();

        return array('products' => $products, 'productsCount' => $productsCount);

    }

}