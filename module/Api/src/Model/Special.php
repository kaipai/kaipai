<?php
namespace Api\Model;

use COM\Model;
class Special extends Model{

    public function fetch($where = array()){
        $select = $this->getSelect();
        $select->join(array('b' => 'Store'), 'Special.storeID = b.storeID', array('storeName'))
            ->where($where);

        return $this->selectWith($select)->current();
    }

    public function getSpecials($where, $page, $limit){
        $select = $this->getSelect();
        $select->join(array('b' => 'ProductCategory'), 'Special.specialProductCategoryID = b.productCategoryID', array('categoryName'));
        $select->join(array('c' => 'Store'), 'Special.storeID = c.storeID', array('storeName', 'storeLogo'));

        $select->where($where);

        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $data = $paginator->getCurrentItems();
        $pages = $paginator->getPages();

        $result = array(
            'data' => $data,
            'pages' => $pages,
        );
        return $result;
    }
}