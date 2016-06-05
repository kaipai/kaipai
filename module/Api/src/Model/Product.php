<?php
namespace Api\Model;

use Base\ConstDir\BaseConst;
use COM\Model;
use Zend\Http\Header\Warning;

class Product extends Model{

    public function getRecommendProducts(){

        $where = array(
            'b.isRecommend' => 1,
            'b.auctionStatus' => BaseConst::AUCTION_STATUS_PROCESSING,
        );
        $order = array('b.instime desc');


        return $this->productFilterOptionModel->getList($where, $order, 0, 18);
    }

    public function fetch($where = array()){
        $select = $this->getSelect();
        $select->join(array('b' => 'ProductCategory'), 'Product.productCategoryID = b.productCategoryID', array('categoryName'));
        $select->join(array('c' => 'Store'), 'Product.storeID = c.storeID', array('storeName'));
        $select->where($where);

        return $this->selectWith($select)->current();
    }

    public function getProducts($where, $page, $limit){
        $select = $this->getSelect();
        $select->where($where);
        $select->order('Instime DESC');
        $paginator = $this->paginate($select, $this->getSql()->getAdapter());
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $data = $paginator->getCurrentItems()->toArray();
        $pages = $paginator->getPages();
        $result = array(
            'data' => $data,
            'pages' => $pages,
        );
        return $result;
    }

    public function getSpecialRecommendProducts($specialID){
        $where = array(
            'specialID' => $specialID,
            'isRecommend' => 1
        );
        $result = $this->setColumns(array('productName', 'listImg', 'productID', 'currPrice'))->select($where)->toArray();
        return $result;
    }

    public function getStoreRecommendProducts($storeID){
        $where = array(
            'storeID' => $storeID,
            'isRecommend' => 1
        );
        $result = $this->setColumns(array('productName', 'listImg', 'productID', 'currPrice'))->select($where)->toArray();
        return $result;
    }
}