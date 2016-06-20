<?php
namespace Api\Model;

use Base\ConstDir\BaseConst;
use Base\Functions\Utility;
use COM\Model;
use Zend\Db\Sql\Predicate\IsNotNull;
use Zend\Http\Header\Warning;

class Product extends Model{

    public function getRecommendProducts(){

        $where = array(
            'b.isRecommend' => 1,
            'b.auctionStatus' => BaseConst::AUCTION_STATUS_PROCESSING,
            'b.isDel' => 0,
        );
        $order = array('b.instime desc');


        return $this->productFilterOptionModel->getList($where, $order, 0, 18);
    }

    public function getIndexRecommendProducts(){

        $where = array(
            'b.isIndexRecommend' => 1,
            'b.auctionStatus' => array(1, 2),
            'b.isDel' => 0,
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

    public function getProducts($where, $page = 1, $limit = 10, $order = ''){
        $where = array_merge($where, array('isDel' => 0));
        $select = $this->getSelect();
        $select->where($where);
        if(!empty($order)){
            $select->order($order);
        }else{
            $select->order('Product.instime DESC');
        }

        $paginator = $this->paginate($select, $this->getSql()->getAdapter());
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $data = $paginator->getCurrentItems()->getArrayCopy();
        $pages = $paginator->getPages();
        foreach($data as $k => $v){
            $data[$k]['leftTime'] = Utility::getLeftTime(time(), $v['endTime']);
        }
        $result = array(
            'data' => $data,
            'pages' => $pages,
        );
        return $result;
    }

    public function getSpecialRecommendProducts($specialID){
        $where = array(
            'specialID' => $specialID,
            'isSpecialRecommend' => 1,
            'auctionStatus' => array(1, 2),
            'isDel' => 0,
        );
        $result = $this->setColumns(array('productName', 'listImg', 'productID', 'currPrice'))->select($where)->toArray();
        return $result;
    }

    public function getStoreRecommendProducts($storeID){
        $where = array(
            'storeID' => $storeID,
            'isStoreRecommend' => 1,
            'auctionStatus' => array(1, 2),
            'isDel' => 0,
        );
        $result = $this->setColumns(array('productName', 'listImg', 'productID', 'currPrice'))->select($where)->toArray();
        return $result;
    }

    public function getExpireProducts(){
        $select = $this->getSelect();
        $select->join(array('b' => 'AuctionMember'), 'Product.productID = b.productID and b.myCurrPrice = Product.currPrice', array('auctionMemberID', 'memberID'), 'left');
        $select->where(array('Product.auctionStatus' => 2, 'Product.endTime < ?' => time(), 'Product.isDel' => 0));
        $products = $this->selectWith($select)->toArray();

        return $products;
    }



}