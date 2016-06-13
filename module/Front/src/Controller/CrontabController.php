<?php
namespace Front\Controller;

use Base\ConstDir\Api\Sms;
use Base\ConstDir\BaseConst;
use Base\Functions\Utility;
use COM\Controller;
use Zend\Db\Sql\Predicate\Between;
use Zend\Db\Sql\Predicate\IsNotNull;
use Zend\Db\Sql\Predicate\IsNull;

class CrontabController extends Controller{

    public function auctionAction(){
        $expireProducts = $this->productModel->getExpireProducts();
        try{
            $this->productModel->beginTransaction();
            foreach($expireProducts as $v){
                $this->productModel->update(array('auctionStatus' => 3), array('productID' => $v['productID']));
                $this->auctionMemberModel->update(array('status' => 2), array('productID' => $v['productID']));
                $this->auctionMemberModel->update(array('status' => 1), array('auctionMemberID' => $v['auctionMemberID']));
            }
            $this->productModel->commit();
        }catch (\Exception $e){
            $this->productModel->rollback();
        }

        return $this->response;
    }

}
