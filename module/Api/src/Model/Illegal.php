<?php
namespace Api\Model;
use COM\Model;
class Illegal extends Model{
    protected $table = 'Error';

    public function getIllegals($page, $limit){
        $select = $this->getSelect();
        $select->join(array('b' => 'MemberInfo'), 'Illegal.memberID = b.memberID', array('nickName'), 'left');
        $select->join(array('c' => 'MemberArticle'), 'Illegal.coreID = c.memberArticleID', array('memberArticleName'));
        $select->join(array('d' => 'MemberArticleComment'), 'Illegal.coreID = d.memberArticleCommentID', array('commentContent'));
        $select->join(array('e' => 'Product'), 'Illegal.coreID = e.productID', array('productName'));
        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $data = $paginator->getCurrentItems()->getArrayCopy();
        $total = $paginator->getTotalItemCount();

        $res = array(
            'data' => $data,
            'total' => $total,
        );

        return $res;
    }
}