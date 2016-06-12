<?php
namespace Api\Model;

use COM\Model;
class Artist extends Model{

    public function getArtists($page, $limit){
        $select = $this->getSelect();
        $select->join(array('b' => 'ArtistCategory'), 'Artist.artistCategoryID = b.artistCategoryID', array('categoryName'));
        $select->where(array('isDel' => 0));

        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $data = $paginator->getCurrentItems()->getArrayCopy();
        $total = $paginator->getTotalItemCount();
        $res = array('data' => $data, 'total' => $total);

        return $res;
    }
}