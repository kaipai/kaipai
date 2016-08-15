<?php
namespace Api\Model;

use COM\Model;
class MemberArticleMark extends Model{

    public function getArticles($where, $page, $limit, $order = ''){
        $where = array_merge($where, array('b.isDel' => 0));
        $select = $this->getSelect();
        $select->columns(array());
        $select->join(array('b' => 'MemberArticle'), 'MemberArticleMark.memberArticleID = b.memberArticleID');
        $select->where($where);
        if(empty($order)){
            $order = 'MemberArticleMark.instime desc';
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