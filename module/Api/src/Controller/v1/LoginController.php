<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\Api\Sms;
use Base\ConstDir\Regexp;
use Base\Functions\Utility;
use COM\Controller\Api;
use Zend\Authentication\Storage\Session;
use Zend\Session\Storage\SessionStorage;

class LoginController extends Api{

    public function doLoginAction(){
        $mobile = $this->postData['mobile'];
        $password = $this->postData['password'];
        if(empty($mobile) || empty($password)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        if(!$this->validateMobile($mobile)) return $this->response(ApiError::MOBILE_VALIDATE_FAILED, ApiError::MOBILE_VALIDATE_FAILED_MSG);

        $select = $this->memberModel->getSelect();
        $select->join(array('b' => 'MemberInfo'), 'Member.memberID = b.memberID')
            ->where(array('Member.mobile' => $mobile, 'Member.password' => $this->memberModel->genPassword($password)));

        $memberInfo = $this->memberModel->selectWith($select)->current();

        if(!empty($memberInfo)){
            $token = $this->tokenModel->select(array('memberID' => $memberInfo['memberID']))->current();
            if(empty($token)){
                $token = array('memberID' => $memberInfo['memberID'], 'token' => uniqid());
                $this->tokenModel->insert($token);
            }
            $memberInfo['token'] = $token['token'];
            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $memberInfo);
        }else{
            return $this->response(ApiError::LOGIN_FAILED, ApiError::LOGIN_FAILED_MSG);
        }

    }

    public function getPicVerifyCodeAction(){
        session_start();
        $imgService = $this->sm->get('COM\Service\ImageService');
        $imgService->buildImageVerify(4, 1, 'gif', 48, 24, $this->sessionVerifyCode);
        exit;
    }

    public function getVerifyCodeAction(){
        session_start();
        $mobile = $this->postData['mobile'];
        $picVerifyCode = $this->postData['picVerifyCode'];

        if(empty($mobile)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        if(!$this->validateMobile($mobile)) return $this->response(ApiError::MOBILE_VALIDATE_FAILED, ApiError::MOBILE_VALIDATE_FAILED_MSG);
        /*if(md5($picVerifyCode) != $_SESSION[$this->sessionVerifyCode]){
            return $this->response(ApiError::REG_PIC_VERIFY_CODE_FAILED, ApiError::REG_PIC_VERIFY_CODE_FAILED_MSG);
        }*/
        $verifyCode = $this->mobileVerifyCodeModel->getVerifyCode();
        $sms = str_replace('{$verifyCode}', $verifyCode, Sms::VERIFY_CODE_MSG);
        $this->smsService->sendSms($mobile, $sms);
        $data = array(
            'mobile' => $mobile,
            'verifyCode' => $verifyCode,
            'expireTime' => time(),
        );
        $this->mobileVerifyCodeModel->insert($data);
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function regAction(){
        $mobile = $this->postData['mobile'];
        $password = $this->postData['password'];
        $confirmPassword = $this->postData['confirmPassword'];
        $nickName = $this->postData['nickName'];
        if(empty($mobile) || empty($password) || empty($confirmPassword)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        if(!$this->validateMobile($mobile)) return $this->response(ApiError::MOBILE_VALIDATE_FAILED, ApiError::MOBILE_VALIDATE_FAILED_MSG);
        if(strlen($password) < 6) return $this->response(ApiError::PASSWORD_LT_SIX_WORDS, ApiError::PASSWORD_LT_SIX_WORDS_MSG);
        if($password != $confirmPassword){
            return $this->response(ApiError::TWICE_PASSWORD_NOT_SIMILAR, ApiError::TWICE_PASSWORD_NOT_SIMILAR_MSG);
        }
        $verifyCode = $this->mobileVerifyCodeModel->select(array('mobile' => $this->postData['mobile'], 'expireTime > ?' => time()))->current();
        if($verifyCode != $this->postData['verifyCode'] && 0){
            return $this->response(ApiError::VERIFY_CODE_INVALID, ApiError::VERIFY_CODE_INVALID_MSG);
        }
        $where = array(
            'nickName' => $nickName
        );
        $existMember = $this->memberInfoModel->select($where)->current();
        if(!empty($existMember)){
            return $this->response(ApiError::MEMBER_EXIST_NICK_NAME, ApiError::MEMBER_EXIST_NICK_NAME_MSG);
        }
        $where = array(
            'mobile' => $mobile
        );
        $existMember = $this->memberInfoModel->select($where)->current();
        if(!empty($existMember)){
            return $this->response(ApiError::MEMBER_EXIST_MOBILE, ApiError::MEMBER_EXIST_MOBILE_MSG);
        }
        $data = array(
            'mobile' => $mobile,
            'password' => $this->memberModel->genPassword($password),
        );
        $this->memberModel->insert($data);
        $memberID = $this->memberModel->getLastInsertValue();
        $memberInfoData = array(
            'memberID' => $memberID
        );
        $this->memberInfoModel->insert($memberInfoData);

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function resetPwdAction(){
        $mobile = $this->postData['mobile'];
        $verifyCode = $this->postData['verifyCode'];
        $newPwd = $this->postData['newPwd'];

        if(empty($mobile) || empty($verifyCode) || empty($newPwd)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        if(!$this->validateMobile($mobile)) return $this->response(ApiError::MOBILE_VALIDATE_FAILED, ApiError::MOBILE_VALIDATE_FAILED_MSG);

        $smsVeriyfCode = $this->mobileVerifyCodeModel->getLastVerifyCode($mobile);
        if($verifyCode == $smsVeriyfCode){
            $set = array(
                'password' => $this->memberModel->genPassword($newPwd),
            );
            $this->memberModel->update($set, array('mobile' => $mobile));
        }

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

}