<?php
namespace Api\Model;

use COM\Model;
class MemberProductInterest extends Model{

    public function getProducts($where, $page, $limit){
        $select = $this->getSelect();
        $select->columns(array('productInterestID'));
        $select->join(array('b' => 'Product'), 'MemberProductInterest.productID = b.productID')
                ->join(array('c' => 'Store'), 'b.storeID = c.storeID', array('storeName', 'storeLogo', 'storeqq'))
                ->join(array('d' => 'AuctionMember'), 'b.productID = d.productID and MemberProductInterest.memberID = d.memberID', array('auctionMemberID'), 'left')
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