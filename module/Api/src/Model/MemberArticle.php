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
        $memberArticles = $paginator->getCurrentItems()->getArrayCopy();
        $memberArticlesCount = $paginator->getTotalItemCount();

        return array('memberArticles' => $memberArticles, 'memberArticlesCount' => $memberArticlesCount);

    }

    public function getArticles($where, $page, $limit, $order = ''){
        $select = $this->getSelect();
        $select->where($where);
        $select->where(array('isDel' => 0));
        if(empty($order)){
            $order = 'MemberArticle.instime desc';
        }

        $select->order($order);
        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $articles = $paginator->getCurrentItems()->getArrayCopy();
        $pages = $paginator->getPages();
        $total = $paginator->getTotalItemCount();

        $res = array(
            'data' => $articles,
            'pages' => $pages,
            'total' => $total,
        );
        return $res;
    }
}