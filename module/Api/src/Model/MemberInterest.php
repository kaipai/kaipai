<?php
namespace Api\Model;
use COM\Model;
class MemberInterest extends Model{
    protected $table = 'MemberInterest';

    public function getInterestMembers($where, $page, $limit){
        $select = $this->getSelect();
        $select->columns(array());
        $select->join(array('b' => 'MemberInfo'), 'MemberInterest.interestedMemberID = b.memberID');
        $select->where($where);

        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $members = $paginator->getCurrentItems()->getArrayCopy();
        $pages = $paginator->getPages();
        $total = $paginator->getTotalItemCount();

        $res = array(
            'data' => $members,
            'pages' => $pages,
            'total' => $total,
        );
        return $res;
    }
}