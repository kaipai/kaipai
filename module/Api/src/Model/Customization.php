<?php
namespace Api\Model;
use COM\Model;

class Customization extends Model{
    protected $table = 'Customization';

    public function getCustomizations($page, $limit){
        $select = $this->getSql()->select();
        $select->join(array('b' => 'Artist'), 'Customization.artistID = b.artistID', array('artistName', 'shortDesc', 'artistAvatar'));
        $select->join(array('c' => 'ArtistCategory'), 'Customization.artistCategoryID = c.artistCategoryID', array('categoryName'));
        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $pages = $paginator->getPages();
        $data = $this->selectWith($select)->toArray();
        $result = array(
            'data' => $data,
            'pages' => $pages,
        );

        return $result;
    }

    public function getAllCustomizations($where){
        $select = $this->getSql()->select();
        $select->join(array('b' => 'Artist'), 'Customization.artistID = b.artistID', array('artistName', 'shortDesc', 'artistAvatar'));
        $select->join(array('c' => 'ArtistCategory'), 'b.artistCategoryID = c.artistCategoryID', array('categoryName'));
        $select->where($where);
        $result = $this->selectWith($select)->toArray();

        return $result;
    }

    public function fetch($where = array()){
        $select = $this->getSql()->select();
        $select->join(array('b' => 'Artist'), 'Customization.artistID = b.artistID', array('artistName', 'shortDesc', 'artistAvatar'));
        $select->join(array('c' => 'ArtistCategory'), 'b.artistCategoryID = c.artistCategoryID', array('categoryName'));
        $select->where($where);

        return $this->selectWith($select)->current();
    }
}