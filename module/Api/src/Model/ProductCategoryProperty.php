<?php
namespace Api\Model;

use COM\Model;
class ProductCategoryProperty extends Model{

    public function getProperties($page, $limit){
        $select = $this->getSql()->select();
        $select->join(array('b' => 'ProductCategory'), 'ProductCategoryProperty.productCategoryID = b.productCategoryID', array('categoryName'));
        $select->order(array('b.productCategoryID asc'));
        $paginator = $this->paginate($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $pages = $paginator->getPages();
        $data = $paginator->getCurrentItems()->getArrayCopy();
        $total = $paginator->getTotalItemCount();
        $result = array(
            'data' => $data,
            'pages' => $pages,
            'total' => $total
        );

        return $result;
    }
}