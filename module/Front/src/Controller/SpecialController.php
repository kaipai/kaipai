<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\BaseConst;
use COM\Controller\Front;
use Zend\Db\Sql\Predicate\Between;

class SpecialController extends Front{

    public function indexAction(){
        $categoryThemeOptions = $this->productCategoryFilterOptionModel->getThemeOptions();
        $where = array(
            'startTime > ?' => time(),
        );
        $specials = $this->specialModel->getSpecials($where, $this->pageNum, $this->limit);

        $this->view->setVariables(array(
            'categoryThemeOptions' => $categoryThemeOptions,
            'specials' => $specials['data'],
            'pages' => $specials['pages'],

        ));
        return $this->view;
    }

    public function todayAction(){
        $filter = $this->queryData['filter'] ? $this->queryData['filter'] : 'today';
        $specialProductCategoryID = $this->queryData['specialProductCategoryID'];
        $page = $this->queryData['page'] ? $this->queryData['page'] : 1;
        $limit = 6;
        $where = array('Special.verifyStatus' => 2, 'Special.auctionStatus' => array(1, 2));
        if($filter == 'today'){
            $where[] = new Between('Special.startTime', strtotime(date('Y-m-d 00:00:00')), strtotime(date('Y-m-d 23:59:59')));
        }elseif($filter == 'tomorrow'){
            $where[] = new Between('Special.startTime', strtotime(date('Y-m-d 00:00:00', strtotime('+1 day'))), strtotime(date('Y-m-d 23:59:59', strtotime('+1 day'))));
        }elseif($filter == 'on'){
            $where['Special.auctionStatus'] = array(1);
        }
        if(!empty($specialProductCategoryID)){
            $where['Special.specialProductCategoryID'] = $specialProductCategoryID;
        }

        $specials = $this->specialModel->getSpecials($where, $page, $limit);

        $recommendProducts = $this->productModel->specialGetRecommendProducts();
        $stores = $this->storeModel->getHotStores(1, 20);

        $ads = $this->adModel->getAdByPosition(BaseConst::AD_POSITION_SPECIAL_INDEX);
        $this->view->setVariables(array(
            'recommendProducts' => $recommendProducts,
            'filter' => $filter,
            'specials' => $specials['data'],
            'pages' => $specials['pages'],
            'specialProductCategoryID' => $specialProductCategoryID,
            'stores' => $stores['data'],
            'ads' => $ads,
        ));
        return $this->view;
    }

    public function detailAction(){
        $specialID = $this->queryData['specialID'];
        $where = array(
            'specialID' => $specialID,
        );

        $specialInfo = $this->specialModel->fetch($where);

        $where = array(
            'SpecialProduct.specialID' => $specialID
        );
        $specialProducts = $this->specialProductModel->getSpecialProducts($where, $this->pageNum, $this->limit);

        $this->view->setVariables(array(
            'specialInfo' => $specialInfo,
            'products' => $specialProducts['data'],
            'pages' => $specialProducts['pages'],
        ));

        return $this->view;
    }

    public function listAction(){
        $where = array();

        $select = $this->specialModel->getSelect();
        $select->where($where);
        $paginator = $this->specialModel->paginate($select);
        $paginator->setCurrentPageNumber(ceil($this->offset / $this->limit) + 1);
        $paginator->setItemCountPerPage($this->limit);
        $dataRows = $paginator->getCurrentItems()->getArrayCopy();
        $dataTotalCount = $paginator->getTotalItemCount();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('rows' => $dataRows, 'total' => $dataTotalCount));

    }

    public function addAction(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
        unset($this->postData['token']);
        $this->postData['storeID'] = $this->memberInfo['storeID'];
        $this->specialModel->insert($this->postData);
        $specialID = $this->specialModel->getLastInsertValue();
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('specialID' => $specialID));
    }

    public function updateAction(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
        unset($this->postData['token']);
        $specialID = $this->postData['specialID'];
        if(empty($specialID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        $where = array(
            'specialID' => $specialID,
            'storeID' => $this->memberInfo['storeID'],
        );
        $this->specialModel->update($this->postData, $where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function delAction(){
        if(empty($this->memberInfo)) return $this->response(ApiError::NEED_LOGIN, ApiError::NEED_LOGIN_MSG);
        unset($this->postData['token']);
        $specialID = $this->postData['specialID'];
        if(empty($specialID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        $where = array(
            'specialID' => $specialID,
            'storeID' => $this->memberInfo['storeID'],
        );

        $this->specialModel->delete($where);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function productAction(){
        $specialID = $this->postData['specialID'];
        if(empty($specialID)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        $where = array(
            'SpecialProduct.specialID' => $specialID
        );
        $select = $this->specialProductModel->getSelect();
        $select->columns(array());
        $select->join(array('b' => 'Product'), 'SpecialProduct.productID = b.productID');
        $select->where($where);

        $paginator = $this->specialProductModel->paginate($select);
        $paginator->setCurrentPageNumber(ceil($this->offset / $this->limit) + 1);
        $paginator->setItemCountPerPage($this->limit);
        $dataRows = $paginator->getCurrentItems()->getArrayCopy();
        $dataTotalCount = $paginator->getTotalItemCount();

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('rows' => $dataRows, 'total' => $dataTotalCount));
    }
}