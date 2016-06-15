<?php
namespace Front\Controller;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\ConstDir\Api\Sms;
use Base\ConstDir\BaseConst;
use Base\Functions\Utility;
use COM\Controller\Front;
use Zend\Authentication\Storage\Session;
use Zend\Http\Cookies;
use Zend\Http\Header\SetCookie;

class LoginController extends Front{
    public function init(){
        if(!empty($this->memberInfo) && in_array($this->actionName, array('do-login', 'reg'))) return $this->redirect()->toUrl('/member/order');
    }

    public function doLoginAction(){
        if(empty($this->postData)) {
            $ad = $this->adModel->getAdByPosition(BaseConst::AD_POSITION_INDEX_THIRD_RIGHT);
            $ad = current($ad);
            $this->view->setVariables(array(
                'ad' => $ad,
            ));
            return $this->view;
        }

        $mobile = $this->postData['mobile'];
        $password = $this->postData['password'];
        $rememberMe = $this->postData['rememberMe'];

        if(empty($mobile) || empty($password)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        if(!$this->validateMobile($mobile)) return $this->response(ApiError::MOBILE_VALIDATE_FAILED, ApiError::MOBILE_VALIDATE_FAILED_MSG);

        $select = $this->memberModel->getSelect();
        $select->join(array('b' => 'MemberInfo'), 'Member.memberID = b.memberID')
            ->where(array('Member.mobile' => $mobile, 'Member.password' => $this->memberModel->genPassword($password)));

        $memberInfo = $this->memberModel->selectWith($select)->current();
        if(!empty($memberInfo)){
            $loginSession = new Session(self::FRONT_PLATFORM, null,null);
            $loginSession->write($memberInfo);
            if($rememberMe){
                $autoCode = md5($memberInfo['memberID'] . Utility::getRandCode(6));
                setcookie('autoCode', $autoCode, strtotime('+1 year'), '/');
                $this->memberInfoModel->update(array('autoCode' => $autoCode), array('memberID' => $memberInfo['memberID']));
            }else{
                setcookie('autoCode', '', time() - 1, '/');
            }
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
        if(md5($picVerifyCode) != $_SESSION[$this->sessionVerifyCode]){
            return $this->response(ApiError::REG_PIC_VERIFY_CODE_FAILED, ApiError::REG_PIC_VERIFY_CODE_FAILED_MSG);
        }
        $verifyCode = $this->mobileVerifyCodeModel->getVerifyCode();
        $sms = str_replace('{$verifyCode}', $verifyCode, Sms::VERIFY_CODE_MSG);
        $this->smsService->sendSms($mobile, $sms);
        $data = array(
            'mobile' => $mobile,
            'verifyCode' => $verifyCode,
            'expireTime' => time() + 600,
        );
        $this->mobileVerifyCodeModel->insert($data);
        return $this->response(ApiSuccess::COMMON_SUCCESS, '验证码已发送');
    }

    public function regAction(){
        if(empty($this->postData)) return $this->view;
        $mobile = $this->postData['mobile'];
        $password = $this->postData['password'];
        $confirmPassword = $this->postData['confirmPassword'];
        $nickName = $this->postData['nickName'];
        if(empty($mobile) || empty($password) || empty($confirmPassword) || empty($nickName)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        if(!$this->validateMobile($mobile)) return $this->response(ApiError::MOBILE_VALIDATE_FAILED, ApiError::MOBILE_VALIDATE_FAILED_MSG);
        if(strlen($password) < 6) return $this->response(ApiError::PASSWORD_LT_SIX_WORDS, ApiError::PASSWORD_LT_SIX_WORDS_MSG);
        if($password != $confirmPassword){
            return $this->response(ApiError::TWICE_PASSWORD_NOT_SIMILAR, ApiError::TWICE_PASSWORD_NOT_SIMILAR_MSG);
        }
        $verifyCode = $this->mobileVerifyCodeModel->getLastVerifyCode($mobile);
        if($verifyCode != $this->postData['verifyCode']){
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
            'memberID' => $memberID,
            'nickName' => $nickName,
            'mobile' => $mobile,
        );
        $this->memberInfoModel->insert($memberInfoData);

        $token = array('memberID' => $memberID, 'token' => uniqid());
        $this->tokenModel->insert($token);
        $memberInfo = array(
            'token' => $token['token'],
            'memberID' => $memberID,
            'nickName' => $nickName
        );

        return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $memberInfo);
    }

    public function resetPwdAction(){
        if(empty($this->postData)) return $this->view;
        $mobile = $this->postData['mobile'];
        $verifyCode = $this->postData['verifyCode'];
        $newPwd = $this->postData['password'];
        $confirmNewPwd = $this->postData['confirmPassword'];

        if(empty($mobile) || empty($verifyCode) || empty($newPwd) || empty($confirmNewPwd)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        if(!$this->validateMobile($mobile)) return $this->response(ApiError::MOBILE_VALIDATE_FAILED, ApiError::MOBILE_VALIDATE_FAILED_MSG);
        if(strlen($newPwd) < 6) return $this->response(ApiError::PASSWORD_LT_SIX_WORDS, ApiError::PASSWORD_LT_SIX_WORDS_MSG);
        if($newPwd != $confirmNewPwd){
            return $this->response(ApiError::TWICE_PASSWORD_NOT_SIMILAR, ApiError::TWICE_PASSWORD_NOT_SIMILAR_MSG);
        }
        $smsVeriyfCode = $this->mobileVerifyCodeModel->getLastVerifyCode($mobile);
        if($verifyCode == $smsVeriyfCode){
            try{
                $set = array(
                    'password' => $this->memberModel->genPassword($newPwd),
                );
                $this->memberModel->update($set, array('mobile' => $mobile));
                return $this->response(ApiSuccess::COMMON_SUCCESS, '密码重置成功, 请用新密码登录');
            }catch (\Exception $e){
                return $this->response(ApiSuccess::COMMON_SUCCESS, '重置失败, 请重试');
            }


        }else{
            return $this->response(ApiError::COMMON_ERROR, '手机验证码验证失败');
        }


    }

    public function regSuccAction(){
        return $this->view;
    }

}