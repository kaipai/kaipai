<?php
namespace Api\Model;

use COM\Model;
class MemberMessage extends Model
{

    public function getMessages($where, $page, $limit, $order = '')
    {
        $select = $this->getSelect();
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
