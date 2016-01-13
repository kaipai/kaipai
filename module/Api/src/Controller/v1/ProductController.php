<?php
namespace Api\Controller\v1;

use Base\Functions\Utility;
use COM\Controller\Api;
class ProductController extends Api{
    private $_productModel;
    public function init(){
        $this->_productModel = $this->sm->get('Api\Model\Product');
    }

    /**
     * 商品列表
     */
    public function listAction(){
        $select = $this->_memberModel->getSelect();
        $paginator = $this->_memberModel->paginate($select);
        $paginator->setCurrentPageNumber(ceil($this->offset / $this->limit) + 1);
        $paginator->setItemCountPerPage($this->limit);
        return $this->response(array('rows' => $paginator->getCurrentItems()->toArray(), 'total' => $paginator->getTotalItemCount()));
    }

}