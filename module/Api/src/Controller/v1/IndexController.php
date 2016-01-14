<?php
namespace Api\Controller\v1;

use Base\ConstDir\BaseConst;
use Base\Functions\Utility;
use COM\Controller\Api;
class IndexController extends Api{

    public function indexAction(){
        $banners = $this->sm->get('Api\Model\Ad')->getList(array('position' => BaseConst::AD_POSITION_BANNER));
        $productCategories = $this->sm->get('Api\Model\ProductCategory')->getList(array(), 'sort DESC');
        $adsLeft = $this->sm->get('Api\Model\Ad')->getList(array('position' => BaseConst::AD_POSITION_INDEX_LEFT));
        $adsRight = $this->sm->get('Api\Model\Ad')->getList(array('position' => BaseConst::AD_POSITION_INDEX_RIGHT));
        $stores = $this->sm->get('Api\Model\Store')->getList();
        $products = $this->sm->get('Api\Model\Product')->getList();
        $articles = $this->sm->get('Api\Model\Article')->getList();

        $response = array(
            'banners' => $banners,
            'productCategories' => $productCategories,
            'adsLeft' => $adsLeft,
            'adsRight' => $adsRight,
            'stores' => $stores,
            'products' => $products,
            'articles' => $articles,
        );
        return $this->response;
    }

}