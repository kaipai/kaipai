<?php
namespace Api\Model;
use COM\Model;
use Zend\Db\Sql\Predicate\Expression;

class Notification extends Model{
    protected $table = 'Notification';

    public function getNotifications($where, $page = 1, $limit = 15){
        $select = $this->getSelect();
        $select->join(array('b' => 'MemberInfo'), 'Notification.memberID = b.memberID', array('nickName'), 'left');
        $select->where($where);
        $select->order('Notification.instime desc');
        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);

        $data = $paginator->getCurrentItems()->getArrayCopy();
        $pages = $paginator->getPages();
        $total = $paginator->getTotalItemCount();

        $res = array(
            'data' => $data,
            'pages' => $pages,
            'total' => $total,
        );
        return $res;
    }

    public function getTotalNotifications($where){
        $select = $this->getSelect();
        $select->columns(array('count' => new Expression('count(*)')));
        $select->where($where);
        $res = $this->selectWith($select)->current();

        return $res['count'];
    }
}