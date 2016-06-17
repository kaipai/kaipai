<?php
namespace Api\Model;

use COM\Model;
class Artist extends Model{

    public function getArtists($where, $page, $limit){
        $where = array_merge($where, array('isDel' => 0));
        $select = $this->getSelect();
        $select->join(array('b' => 'ArtistCategory'), 'Artist.artistCategoryID = b.artistCategoryID', array('categoryName'));
        $select->where($where);

        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $data = $paginator->getCurrentItems()->getArrayCopy();
        $total = $paginator->getTotalItemCount();
        $res = array('data' => $data, 'total' => $total);

        return $res;
    }

    public function getArtistDetail($where){
        $where = array_merge($where, array('isDel' => 0));
        $select = $this->getSelect();
        $select->join(array('b' => 'ArtistCategory'), 'Artist.artistCategoryID = b.artistCategoryID', array('categoryName'));
        $select->where($where);

        $res = $this->selectWith($select)->current();
        return $res;
    }
}