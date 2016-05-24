<?php
namespace Api\Model;

use Base\ConstDir\BaseConst;
use COM\Model;

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
        $select->join(array('b' => 'ProductCategory'), 'Product.productCategoryID = b.productCategoryID', 'categoryName');
        $select->where($where);

        return $this->selectWith($select)->current();
    }

}