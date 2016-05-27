<?php
namespace Api\Model;
use COM\Model;

class Customization extends Model{
    protected $table = 'Customization';

    public function getCustomizations($page, $limit){
        $select = $this->getSql()->select();
        $select->join(array('b' => 'Artist'), 'Customization.artistID = b.artistID', array('artistName', 'shortDesc'));
        $select->join(array('c' => 'ProductCategory'), 'Customization.productCategoryID = c.productCategoryID', array('categoryName'));
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

    public function fetch($where){
        $select = $this->getSql()->select();
        $select->join(array('b' => 'Artist'), 'Customization.artistID = b.artistID', array('artistName', 'shortDesc'));
        $select->join(array('c' => 'ProductCategory'), 'Customization.productCategoryID = c.productCategoryID', array('categoryName'));
        $select->where($where);

        return $this->selectWith($select)->current();
    }
}