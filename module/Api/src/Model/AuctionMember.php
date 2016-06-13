<?php
namespace Api\Model;

use COM\Model;
class AuctionMember extends Model{

    public function existAuctionMember($productID, $memberID){
        $exist = $this->select(array('productID' => $productID, 'memberID' => $memberID))->current();
        if($exist){
            return $exist;
        }else{
            $data = array('productID' => $productID, 'memberID' => $memberID);
            $this->insert($data);
            $data['auctionMemberID'] = $this->getLastInsertValue();

            return $data;
        }
    }

    public function getDetail($where){
        $select = $this->getSelect();

        $select->join(array('b' => 'MemberInfo'), 'b.memberID = AuctionMember.memberID', array('nickName'));
        $select->where($where);

        return $this->selectWith($select)->current();

    }

    public function getAuctionList($memberID){
        $select = $this->getSelect();
        $select->columns(array('proxyPrice', 'myCurrPrice', 'status', 'auctionMemberID'));
        $select->join(array('b' => 'Product'), 'AuctionMember.productID = b.productID');
        $select->join(array('c' => 'Store'), 'b.storeID = c.storeID', array('storeName', 'storeLogo'));
        $select->where(array('AuctionMember.memberID' => $memberID));

        $result = $this->selectWith($select)->toArray();

        return $result;
    }
}