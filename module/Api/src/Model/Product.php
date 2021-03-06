<?php
namespace Api\Model;

use Base\ConstDir\BaseConst;
use Base\Functions\Utility;
use COM\Model;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\IsNotNull;
use Zend\Db\Sql\Where;
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
            'b.recommendStartTime < ?' => time(),
            'b.recommendEndTime > ?' => time(),
            'b.isDel' => 0,
        );

        /*$where = new Where();
        $where->equalTo('b.isIndexRecommend', 1)->equalTo('b.isDel', 0);
        $where->and->nest()->or->nest()->and->isNull('b.specialID')->and->in('b.auctionStatus', array(2))->unnest()->or->nest()->and->isNotNull('b.specialID')->and->in('b.auctionStatus', array(1, 2));*/

        $order = array('b.instime desc');


        return $this->productFilterOptionModel->getList($where, $order, 0, 18);
    }

    public function fetch($where = array()){
        $select = $this->getSelect();
        $select->join(array('b' => 'ProductCategory'), 'Product.productCategoryID = b.productCategoryID', array('categoryName'));
        $select->join(array('c' => 'Store'), 'Product.storeID = c.storeID', array('storeName', 'storeqq'));
        $select->where($where);

        return $this->selectWith($select)->current();
    }

    public function getProducts($where, $page = 1, $limit = 10, $order = ''){

        $select = $this->getSelect();
        $select->join(array('b' => 'Store'), 'Product.storeID = b.storeID', array('storeName'), 'left');
        $select->where($where);
        $select->where(array('Product.isDel' => 0));
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
        $total = $paginator->getTotalItemCount();
        foreach($data as $k => $v){
            $data[$k]['leftTime'] = Utility::getLeftTime(time(), $v['endTime']);
        }
        $result = array(
            'data' => $data,
            'pages' => $pages,
            'total' => $total,
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

    public function getProductIndexRecommendProducts(){
        $where = array(
            'isProductIndexRecommend' => 1,
            //'auctionStatus' => array(1, 2),
            'recommendStartTime < ?' => time(),
            'recommendEndTime > ?' => time(),
            'isDel' => 0,
        );
        $result = $this->setColumns(array('productName', 'listImg', 'productID', 'currPrice'))->select($where)->toArray();
        return $result;
    }

    public function getSpecialTodayRecommendProducts(){
        $where = array(
            'Product.isSpecialTodayRecommend' => 1,
            //'Product.auctionStatus' => array(1, 2),
            'Product.recommendStartTime < ?' => time(),
            'Product.recommendEndTime > ?' => time(),
            'Product.isDel' => 0
        );
        return $this->getProducts($where, 1, 20, array('Product.instime desc'));
    }

    public function getMemberRecommendProducts(){
        $where = array(
            'Product.isMemberRecommend' => 1,
            'Product.recommendStartTime < ?' => time(),
            'Product.recommendEndTime > ?' => time(),
            'Product.isDel' => 0
        );
        $result = $this->getProducts($where, 1, 20, array('Product.instime desc'));
        return $result['data'];
    }

    public function getExpireProducts(){
        $select = $this->getSelect();
        $select->join(array('b' => 'AuctionMember'), 'Product.productID = b.productID and b.myCurrPrice = Product.currPrice', array('auctionMemberID', 'memberID'), 'left');
        $select->where(array('Product.auctionStatus' => 2, 'Product.endTime < ?' => time(), 'Product.isDel' => 0));
        $products = $this->selectWith($select)->toArray();

        return $products;
    }

    public function withdraw($productID){
        try{
            $where = array(
                'productID' => $productID,
            );
            $this->productModel->update(array('auctionStatus' => 0, 'startTime' => new Expression('null'), 'endTime' => new Expression('null')), $where);
            $this->auctionMemberModel->delete($where);
            $this->auctionLogModel->delete($where);
            $this->memberProductInterestModel->delete($where);

            return true;
        }catch (\Exception $e){
            return false;
        }

    }

    public function todayCanPublishProducts($storeID = null, $num = 1, $startTime = null, $endTime = null){
        if(empty($startTime)) {
            $startTime = strtotime(date('Y-m-d 00:00:00'));
        }else{
            $startTime = strtotime(date('Y-m-d 00:00:00', $startTime));
        }
        if(empty($endTime)) {
            $endTime = strtotime(date('Y-m-d 23:59:59'));
        }else{
            $endTime = strtotime(date('Y-m-d 23:59:59', $endTime));
        }
        $select = $this->getSelect();
        $select->columns(array('totalProducts' => new Expression('count(*)')));
        $select->where(array(
            'storeID' => $storeID,
            'auctionStatus' => array(1, 2),
            'startTime > ?' => $startTime,
            'startTime < ?' => $endTime,
        ));
        $data = $this->selectWith($select)->current();

        if($data['totalProducts'] + $num > 5){
            return false;
        }else{
            return true;
        }
    }



}