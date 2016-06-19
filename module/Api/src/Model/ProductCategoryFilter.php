<?php
namespace Api\Model;

use COM\Model;
class ProductCategoryFilter extends Model{

    public function getFilters($page, $limit){
        $select = $this->getSql()->select();
        $select->join(array('b' => 'ProductCategory'), 'ProductCategoryFilter.productCategoryID = b.productCategoryID', array('categoryName'));
        $select->order('b.productCategoryID asc');
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