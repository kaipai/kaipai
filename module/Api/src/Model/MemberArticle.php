<?php
namespace Api\Model;

use COM\Model;
class MemberArticle extends Model{

    public function getList($where = array(), $offset = 0, $limit = 1){
        $select = $this->getSelect();
        $select->from(array('a' => 'MemberArticle'))
            ->join(array('b' => 'MemberInfo'), 'a.memberID = b.memberID')
            ->where($where)
            ->limit($limit)
            ->offset($offset);
        $paginator = $this->memberArticleModel->paginate($select);
        $paginator->setCurrentPageNumber(ceil($offset / $limit) + 1);
        $paginator->setItemCountPerPage($limit);
        $memberArticles = $paginator->getCurrentItems()->toArray();
        $memberArticlesCount = $paginator->getTotalItemCount();

        return array('memberArticles' => $memberArticles, 'memberArticlesCount' => $memberArticlesCount);

    }
}