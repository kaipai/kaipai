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
        $verifyCode = $this->postData['verifyCode'];
        $smsCode = $this->mobileVerifyCodeModel->select(array('mobile' => $mobile, 'expireTime > ?' => time()))->current();
        if(empty($verifyCode) || $verifyCode != $smsCode){
            return $this->response(ApiError::VERIFY_CODE_AUTH_FAILED, ApiError::VERIFY_CODE_AUTH_FAILED_MSG);
        }

        $select = $this->memberModel->getSelect();
        $select->from(array('a' => 'Member'))
            ->join(array('b' => 'MemberInfo'), 'a.memberID = b.memberID')
            ->where(array('a.mobile' => $mobile, 'a.password' => $this->memberModel->genPwd($password)));

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
        $verifyCode = $this->memberModel->getVerifyCode();
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