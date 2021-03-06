<?php
namespace Api\Model;

use COM\Model;
class Store extends Model{
    public function getStores($page, $limit, $where = array(), $order = ''){
        $select = $this->getSelect();
        $select->join(array('b' => 'StoreLevel'), 'Store.level = b.level', array('levelName'), 'left');
        $select->join(array('c' => 'MemberInfo'), 'Store.memberID = c.memberID', array('nickName'));
        $select->where($where);
        if(empty($order)) $order = 'Store.storeID desc';
        $select->order($order);
        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $data = $paginator->getCurrentItems()->getArrayCopy();
        $total = $paginator->getTotalItemCount();

        $res = array(
            'data' => $data,
            'total' => $total
        );
        return $res;
    }

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

    public function getRecommendStores($page = 1, $limit = 20){
        $select = $this->getSelect();
        $select->where(array('isRecommend' => 1));

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

    public function fetch($where = array()){
        $select = $this->getSelect();
        $select->join(array('b' => 'StoreLevel'), 'Store.level = b.level', array('fees'), 'left');
        $select->where($where);

        return $this->selectWith($select)->current();
    }

}