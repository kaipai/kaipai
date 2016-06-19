<?php
namespace Api\Model;

use COM\Model;
class ProductCategory extends Model{


    public function getCategories($page, $limit){
        $select = $this->getSql()->select();
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