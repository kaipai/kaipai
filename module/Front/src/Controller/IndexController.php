<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\Api\Sms;
use Base\ConstDir\BaseConst;
use Base\Functions\Utility;
use COM\Controller\Front;

class IndexController extends Front{

    public function indexAction(){
        $banners = $this->adModel->getAdByPosition(BaseConst::AD_POSITION_INDEX_BANNER);
        $indexSecondAd = $this->adModel->getAdByPosition(BaseConst::AD_POSITION_INDEX_SECOND);
        $indexThirdLeftAd = $this->adModel->getAdByPosition(BaseConst::AD_POSITION_INDEX_THIRD_LEFT);
        $indexThirdRightAd = $this->adModel->getAdByPosition(BaseConst::AD_POSITION_INDEX_THIRD_RIGHT);

        $stores = $this->storeModel->getRecommendStores();
        $stores = $stores['data'];
        $products = $this->productModel->getIndexRecommendProducts();

        $indexRecommendArticles = $this->articleModel->getIndexRecommendArticles();
        foreach($indexRecommendArticles as $k => $v){
            if(!empty($v['url'])){
                preg_match_all('/memberArticleID=([\d]*)/', $v['url'], $matches);
                $memberArticleID = $matches[1][0];
                if(!empty($memberArticleID)){
                    $info = $this->memberArticleModel->setColumns(array('memberArticleContent'))->select(array('memberArticleID' => $memberArticleID))->current();
                    $v['articleContent'] = $info['memberArticleContent'];
                }
            }
            $indexRecommendArticles[$k]['articleContent'] = Utility::mbCutStr(Utility::getBodyText($v['articleContent']), 50);
        }
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
            $result = $fileService/*->setThumb(array('width' => 263, 'height' => 263))*/->upload($this->request);
            $result = json_decode($result, true);
            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('picPath' => $result[0]['path']));

        }catch(\Exception $e){
            return $this->response(ApiError::FILE_UPLOAD_FAILED, ApiError::FILE_UPLOAD_FAILED_MSG);
        }
    }

    public function getVerifyCodeAction(){
        $mobile = $this->postData['mobile'];

        if(empty($mobile)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        if(!$this->validateMobile($mobile)) return $this->response(ApiError::MOBILE_VALIDATE_FAILED, ApiError::MOBILE_VALIDATE_FAILED_MSG);
        $verifyCode = $this->mobileVerifyCodeModel->getVerifyCode();
        $sms = str_replace('{$verifyCode}', $verifyCode, Sms::VERIFY_CODE_MSG);
        $this->smsService->sendSms($mobile, $sms);
        $data = array(
            'mobile' => $mobile,
            'verifyCode' => $verifyCode,
            'expireTime' => time() + 600,
        );
        $this->mobileVerifyCodeModel->insert($data);
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function postErrorAction(){
        if(!empty($this->memberInfo)) $this->postData['memberID'] = $this->memberInfo['memberID'];
        $this->errorModel->insert($this->postData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, '提交成功');
    }

    public function postIllegalAction(){
        if(!empty($this->memberInfo)) $this->postData['memberID'] = $this->memberInfo['memberID'];
        if($this->postData['type'] == 1){
            $this->memberArticleModel->update(array('isIllegal' => 2), array('memberArticleID' => $this->postData['coreID']));
        }elseif($this->postData['type'] == 2){
            $this->memberArticleCommentModel->update(array('isIllegal' => 2), array('memberArticleCommentID' => $this->postData['coreID']));
        }
        $this->illegalModel->insert($this->postData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, '举报成功');
    }

}