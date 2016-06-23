<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\BaseConst;
use COM\Controller\Front;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Between;

class SpecialController extends Front{

    public function indexAction(){
        $categoryThemeOptions = $this->productCategoryFilterOptionModel->getThemeOptions();
        $specialProductCategoryID = $this->queryData['specialProductCategoryID'];
        $date = $this->queryData['date'];
        if(empty($date)) $date = date('Y-m-d');
        $where = array(
            'Special.startTime > ?' => strtotime($date . ' 00:00:00'),
            'Special.startTime < ?' => strtotime($date . ' 23:59:59'),
        );
        if(!empty($specialProductCategoryID)){
            $where['Special.specialProductCategoryID'] = $specialProductCategoryID;
        }
        
        $specials = $this->specialModel->getSpecials($where, $this->pageNum, $this->limit, $order = 'Special.startTime asc');
        $this->view->setVariables(array(
            'categoryThemeOptions' => $categoryThemeOptions,
            'specials' => $specials['data'],
            'pages' => $specials['pages'],
            'specialProductCategoryID' => $specialProductCategoryID,
            'date' => $date,

        ));
        return $this->view;
    }

    public function todayAction(){
        $filter = $this->queryData['filter'] ? $this->queryData['filter'] : 'today';

        $page = $this->queryData['page'] ? $this->queryData['page'] : 1;
        $limit = 6;
        $where = array('Special.verifyStatus' => 2, 'Special.auctionStatus' => array(1, 2));
        if($filter == 'today'){
            $where['Special.startTime < ?'] = strtotime(date('Y-m-d 23:59:29'));
        }elseif($filter == 'tomorrow'){
            $where['Special.startTime > ?'] = strtotime(date('Y-m-d 23:59:59'));
            $where['Special.startTime < ?'] = strtotime(date('Y-m-d 23:59:59', strtotime('+1 day')));
        }elseif($filter == 'on'){
            $where['Special.auctionStatus'] = array(1);
        }


        $specials = $this->specialModel->getSpecials($where, $page, $limit, $order = 'Special.startTime asc');


        $recommendProducts = $this->productModel->getProducts(array('Product.isSpecialRecommend' => 1), 1, 20, array('Product.instime desc'));

        $stores = $this->storeModel->getHotStores(1, 20);

        $ads = $this->adModel->getAdByPosition(BaseConst::AD_POSITION_SPECIAL_INDEX);
        $this->view->setVariables(array(
            'recommendProducts' => $recommendProducts['data'],
            'filter' => $filter,
            'specials' => $specials['data'],
            'pages' => $specials['pages'],

            'stores' => $stores['data'],
            'ads' => $ads,
        ));
        return $this->view;
    }

    public function detailAction(){
        $specialID = $this->queryData['specialID'];
        $order = $this->queryData['order'];
        $where = array(
            'specialID' => $specialID,
        );

        $specialInfo = $this->specialModel->fetch($where);

        $where = array(
            'Product.specialID' => $specialID
        );
        $order = !empty($order) ? 'Product.' . $order . ' desc' : 'Product.instime desc';
        $specialProducts = $this->productModel->getProducts($where, 1, 20, $order);

        $products = $specialProducts['data'];
        foreach($products as $v){
            $specialInfo['auctionCount'] += $v['auctionCount'];
        }
        $this->specialModel->update(array('viewCount' => new Expression('viewCount+1')), array('specialID' => $specialID));
        $this->view->setVariables(array(
            'specialInfo' => $specialInfo,
            'products' => $products,
            'pages' => $specialProducts['pages'],
            'order' => $order,
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