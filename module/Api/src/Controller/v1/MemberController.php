<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\Functions\Utility;
use COM\Controller\Api;
class MemberController extends Api{

    public function init(){
        if(empty($this->memberInfo)) return $this->_return(ApiError::NEED_LOGININ, ApiError::NEED_LOGIN_MSG);
    }

    public function indexAction(){
        $memberID = $this->memberInfo['memberID'];
        $memberInfo = $this->memberInfoModel->select(array('MemberID' => $memberID))->current();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('memberInfo' => $memberInfo));
    }

    public function profileAction(){

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function articleAction(){
        $select = $this->memberArticleModel->getSelect();
        $select->from(array('a' => 'MemberArticle'))
            ->join(array('b' => 'MemberInfo'), 'a.memberID = b.memberID');
        $paginator = $this->memberArticleModel->paginate($select);
        $paginator->setCurrentPageNumber(ceil($this->offset / $this->limit) + 1);
        $paginator->setItemCountPerPage($this->limit);
        $articles = $paginator->getCurrentItems()->toArray();
        $articlesCount = $paginator->getTotalItemCount();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('articles' => $articles, 'articlesCount' => $articlesCount));
    }

    public function zoneAction(){
        $zoneInfo = $this->memberZoneModel->select(array('memberID' => $this->memberInfo['memberID']))->current();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('zoneInfo' => $zoneInfo));
    }

    public function interestProductAction(){
        $select = $this->memberProductInterestModel->getSelect();
        $select->from(array('a' => 'MemberProductInterest'))
            ->join(array('b' => 'Product'), 'a.productID = b.productID')
            ->where(array('a.memberID' => $this->memberInfo['memberID']));

        $interestProducts = $this->memberProductInterest->selectWith($select)->toArray();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('interestProducts' => $interestProducts));
    }

    public function interestStoreAction(){
        $select = $this->memberStoreInterestModel->getSelect();
        $select->from(array('a' => 'MemberStoreInterest'))
            ->join(array('b' => 'Store'), 'a.storeID = b.storeID')
            ->where(array('a.memberID' => $this->memberInfo['memberID']));

        $interestStores = $this->memberStoreInterest->selectWith($select)->toArray();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('interestStores' => $interestStores));
    }

}