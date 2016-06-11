<?php
namespace Api\Model;

use COM\Model;
class MemberStoreInterest extends Model{
    public function getStores($where, $page, $limit){
        $select = $this->getSelect();
        $select->columns(array());
        $select->join(array('b' => 'Store'), 'MemberStoreInterest.storeID = b.storeID')
                ->where($where);
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