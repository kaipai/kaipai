<?php
namespace Api\Model;

use COM\Model;
class MemberArticleComment extends Model{

    public function getComments($where, $page, $limit, $order = ''){
        $select = $this->getSelect();
        $select->join(array('b' => 'MemberInfo'), 'MemberArticleComment.senderID = b.memberID', array('nickName'));
        $select->join(array('c' => 'MemberArticle'), 'MemberArticleComment.memberArticleID = c.memberArticleID', array('memberArticleName'));
        $select->where($where);

        if(empty($order)){
            $order = 'MemberArticleComment.instime desc';
        }

        $select->order($order);
        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $comments = $paginator->getCurrentItems()->getArrayCopy();
        $pages = $paginator->getPages();
        $total = $paginator->getTotalItemCount();

        $res = array(
            'data' => $comments,
            'pages' => $pages,
            'total' => $total,
        );
        return $res;
    }
}