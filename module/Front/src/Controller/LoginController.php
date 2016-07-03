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
            $ad = $this->adModel->getAdByPosition(BaseConst::AD_POSITION_LOGIN_AD);
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
            if(!$this->memberInfoModel->isAvailable($memberInfo)){
                return $this->response(ApiError::COMMON_ERROR, '账号被封停');
            }
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

    public function qqLoginAction(){
        $qqOpenID = $this->queryData['qqOpenID'];
        $accessToken = $this->queryData['accessToken'];
        $response = Utility::curl('https://graph.qq.com/user/get_user_info?oauth_consumer_key=101328212&access_token=' . $accessToken . '&openid=' . $qqOpenID . '&format=json', array(), 'get');
        $response = json_decode($response, true);

        if($response['ret'] < 0){
            return $this->response(ApiError::COMMON_ERROR, '接口调用失败');
        }else{
            $nickName = $response['nickname'];
        }
        if(empty($qqOpenID) || empty($nickName)) return $this->response(ApiError::COMMON_ERROR, '缺少参数');
        try{
            $member = array(
                'qqOpenID' => $qqOpenID,
            );
            $exist = $this->memberModel->select($member)->current();
            if(empty($exist)){
                $this->memberModel->beginTransaction();
                $this->memberModel->insert($member);
                $memberID = $this->memberModel->getLastInsertValue();
                $infoData = array(
                    'memberID' => $memberID,
                    'nickName' => $nickName
                );
                $this->memberInfoModel->insert($infoData);
                $this->memberModel->commit();
            }else{
                $memberID = $exist['memberID'];
            }
            $memberInfo = $this->memberInfoModel->select(array('memberID' => $memberID))->current();
            $loginSession = new Session(self::FRONT_PLATFORM, null,null);
            $loginSession->write($memberInfo);

            return $this->redirect()->toUrl('/');
        }catch (\Exception $e){
            $this->memberModel->rollback();
            return $this->redirect()->toUrl('/login/do-login');
        }
    }

    public function wxLoginAction(){
        $code = $this->queryData['code'];
        $token = Utility::curl('https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx8599ed3526a343ea&secret=bf4aa929f93736b3f09c177ed8e609ab&code=' . $code . '&grant_type=authorization_code', array(), 'get');
        $token = json_decode($token, true);
        var_dump($token);die;
        if(!empty($token['access_token'])){
            $userInfo = Utility::curl('https://api.weixin.qq.com/sns/userinfo?access_token=' . $token['access_token'] . '&openid=' . $token['openid'], array(), 'get');
            $userInfo = json_decode($userInfo, true);
            if(!empty($userInfo['nickName'])){
                $nickName = $userInfo['nickName'];
                $member = array(
                    'nickName' => $nickName,
                );
                $exist = $this->memberModel->select($member)->current();
                if(empty($exist)){
                    $this->memberModel->beginTransaction();
                    $this->memberModel->insert($member);
                    $memberID = $this->memberModel->getLastInsertValue();
                    $infoData = array(
                        'memberID' => $memberID,
                        'nickName' => $nickName
                    );
                    $this->memberInfoModel->insert($infoData);
                    $this->memberModel->commit();
                }else{
                    $memberID = $exist['memberID'];
                }
                $memberInfo = $this->memberInfoModel->select(array('memberID' => $memberID))->current();
                $loginSession = new Session(self::FRONT_PLATFORM, null,null);
                $loginSession->write($memberInfo);

                return $this->redirect()->toUrl('/');
            }
        }
        return $this->redirect()->toUrl('/login/do-login');
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
        //$confirmPassword = $this->postData['confirmPassword'];
        $nickName = $this->postData['nickName'];
        if(empty($mobile) || empty($password) || /*empty($confirmPassword) ||*/ empty($nickName)) return $this->response(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);
        if(!$this->validateMobile($mobile)) return $this->response(ApiError::MOBILE_VALIDATE_FAILED, ApiError::MOBILE_VALIDATE_FAILED_MSG);
        if(strlen($password) < 6) return $this->response(ApiError::PASSWORD_LT_SIX_WORDS, ApiError::PASSWORD_LT_SIX_WORDS_MSG);
        /*if($password != $confirmPassword){
            return $this->response(ApiError::TWICE_PASSWORD_NOT_SIMILAR, ApiError::TWICE_PASSWORD_NOT_SIMILAR_MSG);
        }*/
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
        $loginSession = new Session(self::FRONT_PLATFORM, null,null);
        $loginSession->write($memberInfo);

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
        return $this->redirect()->toUrl('/');
        return $this->view;
    }

}