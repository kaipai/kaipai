<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use Base\Functions\Utility;
use COM\Controller\Api;
use Zend\Authentication\Storage\Session;

class LoginController extends Api{



    /**
     * 登录
     */
    public function doLoginAction(){
        $mobile = $this->postData['mobile'];
        $password = $this->postData['password'];
        $verifyCode = $this->postData['verifyCode'];

        if($verifyCode != 1){
            return $this->response(ApiError::VERIFY_CODE_AUTH_FAILED, ApiError::VERIFY_CODE_AUTH_FAILED_MSG);
        }

        $select = $this->memberModel->getSelect();
        $select->from(array('a' => 'Member'))
            ->join(array('b' => 'MemberInfo'), 'a.memberID = b.memberID')
            ->where(array('a.mobile' => $mobile, 'a.password' => $password));

        $memberInfo = $this->memberModel->selectWith($select)->current();

        if(!empty($memberInfo)){
            $session = new Session(self::FRONT_PLATFORM);
            $session->write($memberInfo);
            return $this->response(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, $memberInfo);
        }else{
            return $this->response(ApiError::LOGIN_FAILED, ApiError::LOGIN_FAILED_MSG);
        }

    }

    /**
     * 获取验证码
     */
    public function getVerifyCodeAction(){
        $mobile = $this->postData['mobile'];
        
        return $this->response();
    }
}