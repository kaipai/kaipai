<?php
namespace Api\Model;

use COM\Model;
class MemberArticle extends Model{

    public function getList($where = array(), $order = null, $offset = null, $limit = null){
        $select = $this->getSelect();
        $select->join(array('b' => 'MemberInfo'), 'MemberArticle.memberID = b.memberID', array('nickName'))
            ->where($where)
            ->offset($offset)
            ->limit($limit);

        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber(ceil($offset / $limit) + 1);
        $paginator->setItemCountPerPage($limit);
        $memberArticles = $paginator->getCurrentItems();
        $memberArticlesCount = $paginator->getTotalItemCount();

        return array('memberArticles' => $memberArticles, 'memberArticlesCount' => $memberArticlesCount);

    }
}