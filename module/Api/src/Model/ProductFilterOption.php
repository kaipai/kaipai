<?php
namespace Api\Model;

use Base\Functions\Utility;
use COM\Model;
use Zend\Db\Sql\Select;

class ProductFilterOption extends Model{

    public function getList($where = array(), $order = null, $offset = null, $limit = null){
        if(empty($where['ProductFilterOption.productCategoryFilterOptionID'])){

            $select = new Select();
            $select->from(array('b' => 'Product'));
            $select->join(array('c' => 'ProductCategory'), 'b.productCategoryID = c.productCategoryID', array('categoryName'));
            $select->where($where);
            $select->offset($offset);
            $select->limit($limit);
            $select->order($order);
        }else{
            $select = $this->getSelect();
            $select->columns(array())
                ->join(array('b' => 'Product'), 'ProductFilterOption.productID = b.productID')
                ->join(array('c' => 'ProductCategory'), 'b.productCategoryID = c.productCategoryID', array('categoryName'))
                ->where($where)
                ->offset($offset)
                ->limit($limit)
                ->group(array('ProductFilterOption.productID'))
                ->having('count(ProductFilterOption.productID) > ' . (count($where['ProductFilterOption.productCategoryFilterOptionID']) - 1));
            $select->order($order);
        }
        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber(ceil($offset / $limit) + 1);
        $paginator->setItemCountPerPage($limit);
        $products = $paginator->getCurrentItems()->getArrayCopy();
        $productsCount = $paginator->getTotalItemCount();

        foreach($products as $k => $v){
            $products[$k]['leftTime'] = Utility::getLeftTime(time(), $v['endTime']);
        }

        return array('products' => $products, 'productsCount' => $productsCount);

    }

}