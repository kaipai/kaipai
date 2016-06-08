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
}