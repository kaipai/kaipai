<?php
namespace Api\Model;

use COM\Model;
class SpecialProduct extends Model{

    public function getSpecialProducts($where, $page, $limit){
        $select = $this->getSelect();
        $select->columns(array());
        $select->join(array('b' => 'Product'), 'SpecialProduct.productID = b.productID');
        $select->where($where);

        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $data = $paginator->getCurrentItems()->getArrayCopy();
        $pages = $paginator->getPages();
        $result = array(
            'data' => $data,
            'pages' => $pages,
        );

        return $result;
    }

}