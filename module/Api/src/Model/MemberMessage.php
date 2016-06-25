<?php
namespace Api\Model;

use COM\Model;
class MemberMessage extends Model
{

    public function getMessages($where, $page = 1, $limit = 15, $order = '')
    {
        $select = $this->getSelect();
        $select->join(array('b' => 'MemberInfo'), 'MemberMessage.senderID = b.memberID', array('senderName' => 'nickName'));
        $select->join(array('c' => 'MemberInfo'), 'MemberMessage.memberID = c.memberID', array('receiverName' => 'nickName'));
        $select->where($where);
        if (empty($order)) {
            $order = 'MemberMessage.instime desc';
        }

        $select->order($order);
        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $messages = $paginator->getCurrentItems()->getArrayCopy();
        $pages = $paginator->getPages();
        $total = $paginator->getTotalItemCount();

        $res = array(
            'data' => $messages,
            'pages' => $pages,
            'total' => $total,
        );
        return $res;
    }
}
