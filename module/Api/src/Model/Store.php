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

    public function getHotStores($page, $limit){
        $select = $this->getSelect();
        $select->where(array('isHot' => 1));

        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $hotStores = $paginator->getCurrentItems()->getArrayCopy();
        $pages = $paginator->getPages();


        $result = array(
            'data' => $hotStores,
            'pages' => $pages,

        );

        return $result;

    }

}