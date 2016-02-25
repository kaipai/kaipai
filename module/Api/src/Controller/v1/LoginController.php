<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\Api\Sms;
use Base\Functions\Utility;
use COM\Controller\Api;
use Zend\Authentication\Storage\Session;

class LoginController extends Api{

    public function doLoginAction(){
        $mobile = $this->postData['mobile'];
        $password = $this->postData['password'];
        /*$verifyCode = $this->postData['verifyCode'];
        $smsCode = $this->mobileVerifyCodeModel->select(array('mobile' => $mobile, 'expireTime > ?' => time()))->current();
        if(empty($verifyCode) || $verifyCode != $smsCode){
            return $this->response(ApiError::VERIFY_CODE_AUTH_FAILED, ApiError::VERIFY_CODE_AUTH_FAILED_MSG);
        }*/

        $select = $this->memberModel->getSelect();
        $select->join(array('b' => 'MemberInfo'), 'Member.memberID = b.memberID')
            ->where(array('Member.mobile' => $mobile, 'Member.password' => $this->memberModel->genPwd($password)));

        $memberInfo = $this->memberModel->selectWith($select)->current();

        if(!empty($memberInfo)){
            $session = new Session(self::FRONT_PLATFORM);
            $session->write($memberInfo);
            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $memberInfo);
        }else{
            return $this->response(ApiError::LOGIN_FAILED, ApiError::LOGIN_FAILED_MSG);
        }

    }

    public function getVerifyCodeAction(){
        $mobile = $this->postData['mobile'];
        $verifyCode = $this->mobileVerifyCodeModel->getVerifyCode();
        $sms = str_replace(Sms::VERIFY_CODE_MSG, '{$verifyCode}', $verifyCode);
        $this->smsService->sendSms($mobile, $sms);
        $data = array(
            'verifyCode' => $verifyCode,
            'expireTime' => time(),
        );
        $this->mobileVerifyCodeModel->insert($data);
        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }

    public function regAction(){
        $password = $this->postData['password'];
        $confirmPassword = $this->postData['confirmPassword'];
        if($password != $confirmPassword){
            return $this->response(ApiError::TWICE_PASSWORD_NOT_SIMILAR, ApiError::TWICE_PASSWORD_NOT_SIMILAR_MSG);
        }
        $verifyCode = $this->mobileVerifyCodeModel->select(array('mobile' => $this->postData['mobile'], 'expireTime > ?' => time()))->current();
        if($verifyCode != $this->postData['verifyCode']){
            return $this->response(ApiError::VERIFY_CODE_INVALID, ApiError::VERIFY_CODE_INVALID_MSG);
        }
        $data = array(
            'mobile' => $this->postData['mobile'],
            'password' => $this->memberModel->genPwd($password),
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
        $smsVeriyfCode = $this->mobileVerifyCodeModel->getLastVerifyCode($mobile);
        if($verifyCode == $smsVeriyfCode){
            $set = array(
                'password' => $this->memberModel->genPwd($newPwd),
            );
            $this->memberModel->update($set, array('mobile' => $mobile));
        }

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG);
    }
}