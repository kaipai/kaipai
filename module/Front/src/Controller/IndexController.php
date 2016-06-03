<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\BaseConst;
use COM\Controller\Front;

class IndexController extends Front{

    public function indexAction(){
        $banners = $this->adModel->getAdByPosition(BaseConst::AD_POSITION_INDEX_BANNER);
        $indexSecondAd = $this->adModel->getAdByPosition(BaseConst::AD_POSITION_INDEX_SECOND);
        $indexThirdLeftAd = $this->adModel->getAdByPosition(BaseConst::AD_POSITION_INDEX_THIRD_LEFT);
        $indexThirdRightAd = $this->adModel->getAdByPosition(BaseConst::AD_POSITION_INDEX_THIRD_RIGHT);

        $stores = $this->storeModel->select(array())->toArray();
        $products = $this->productModel->getRecommendProducts();

        $indexRecommendArticles = $this->articleModel->getIndexRecommendArticles();
        $indexArticleList = $this->articleModel->getIndexArticleList();

        $this->view->setVariables(array(
            'products' => $products['products'],
            'banners' => $banners,
            'stores' => $stores,
            'indexSecondAd' => $indexSecondAd,
            'indexThirdLeftAd' => $indexThirdLeftAd,
            'indexThirdRightAd' => $indexThirdRightAd,
            'indexRecommendArticles' => $indexRecommendArticles,
            'indexArticleList' => $indexArticleList,

        ));
        return $this->view;
    }

    public function uploadAction(){
        $fileService = $this->sm->get('COM\Service\FileService');
        $result = $fileService->upload($this->request);
        if(!empty($result)) $result = json_decode($result, true);
        $imgPath = !empty($result[0]['path']) ? $result[0]['path'] : '';

        return $this->view;
    }

    public function contactAction(){
        return $this->view;
    }

    public function hotStoresAction(){
        $hotStores = $this->storeModel->getHotStores($this->pageNum, 1);

        $this->view->setVariables(array(
            'hotStores' => $hotStores['data'],
            'pages' => $hotStores['pages'],
        ));

        return $this->view;
    }

    public function getDayAction(){
        $year = $this->postData['year'];
        $month = $this->postData['month'];
        $days = date('t', strtotime($year . '-' . $month . '-01'));

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $days);
    }

    public function fileUploadAction(){
        try{
            $fileService = $this->sm->get('COM\Service\FileService');
            //$result = $fileService->setThumb(array('width' => 100, 'height' => 100))->upload($this->request);
            $result = $fileService->upload($this->request);
            $result = json_decode($result, true);
            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('picPath' => $result[0]['path']));

        }catch(\Exception $e){
            return $this->response(ApiError::FILE_UPLOAD_FAILED, ApiError::FILE_UPLOAD_FAILED_MSG);
        }
    }
}