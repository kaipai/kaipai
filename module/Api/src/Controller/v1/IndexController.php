<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\BaseConst;
use Base\Functions\Utility;
use COM\Controller\Api;
class IndexController extends Api{

    public function indexAction(){
        $banners = $this->adModel->getList(array('position' => BaseConst::AD_POSITION_BANNER));
        $productCategories = $this->productCategoryModel->getList(array(), 'sort DESC');
        $adsLeft = $this->adModel->getList(array('position' => BaseConst::AD_POSITION_INDEX_LEFT));
        $adsRight = $this->adModel->getList(array('position' => BaseConst::AD_POSITION_INDEX_RIGHT));
        $stores = $this->storeModel->getList();
        $products = $this->productModel->getList();
        $articles = $this->articleModel->getList();

        $response = array(
            'banners' => $banners,
            'productCategories' => $productCategories,
            'adsLeft' => $adsLeft,
            'adsRight' => $adsRight,
            'stores' => $stores,
            'products' => $products,
            'articles' => $articles,
        );
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $response);
    }

}