<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiSuccess;
use Base\Functions\Utility;
use COM\Controller\Api;
class MemberController extends Api{

    public function indexAction(){
        $memberID = $this->loginInfo['MemberID'];
        $memberInfo = $this->memberInfoModel->select(array('MemberID' => $memberID))->current();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('memberInfo' => $memberInfo));
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

    }

    public function interestProductAction(){

    }

    public function interestStoreAction(){

    }

}