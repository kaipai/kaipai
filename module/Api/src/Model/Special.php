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

    public function getSpecials($where, $page = 1, $limit = 10, $order = 'Special.instime desc'){
        $select = $this->getSelect();
        $select->join(array('b' => 'ProductCategory'), 'Special.specialProductCategoryID = b.productCategoryID', array('categoryName'));
        $select->join(array('c' => 'Store'), 'Special.storeID = c.storeID', array('storeName', 'storeLogo'));

        $select->where($where);
        $select->order($order);
        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $data = $paginator->getCurrentItems()->getArrayCopy();
        $pages = $paginator->getPages();
        $total = $paginator->getTotalItemCount();

        $result = array(
            'data' => $data,
            'pages' => $pages,
            'total' => $total
        );
        return $result;
    }

    public function getFullInfo($where){
        $select = $this->getSelect();
        $select->join(array('b' => 'Store'), 'Special.storeID = b.storeID', array('storeName'));
        $select->join(array('c' => 'MemberInfo'), 'b.storeID = c.storeID', array('memberID'));
        $select->where($where);
        $res = $this->selectWith($select)->current();

        return $res;
    }
}