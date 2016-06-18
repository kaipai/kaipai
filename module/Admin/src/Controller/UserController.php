<?php
namespace Admin\Controller;

use Base\ConstDir\Admin\AdminError;
use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Admin;

class UserController extends Admin
{
    public function modifypwdAction()
    {
        $post = $this->request->getPost();
        if (!$this->request->isPost()) {
            $this->view->setVariable("loginInfo", $this->adminInfo);
            return $this->view;
        } else {
            if (empty($this->adminInfo)) {
                return $this->response(ApiError::COMMON_ERROR, '请登录');
            } else {
                if (empty($post['oldPassword']) || empty($post['newPassword']) || empty($post['confirmPassword'])) {
                    return $this->response(AdminError::COMMON_ERROR, '新、旧和确认密码不可为空');
                }
                if ($post['newPassword'] != $post['confirmPassword']) return $this->response(AdminError::COMMON_ERROR, '新密码和确认密码不一致');
                if (md5($post['oldPassword']) != $this->adminInfo['passwd']) {
                    return $this->response(AdminError::COMMON_ERROR, '老密码错误');
                }
                $set['passwd'] = md5($post['newPassword']);
                $where['adminID'] = $this->adminInfo['adminID'];
                $status = $this->adminModel->update($set, $where);
                if ($status) {
                    $session = new \Zend\Authentication\Storage\Session(self::ADMIN_PLATFORM, null, null);
                    $loginInfo = $session->read();
                    $this->adminInfo['passwd'] = $loginInfo['passwd'] = $set['passwd'];
                    $session->clear();
                    $session->write($loginInfo);
                    return $this->response(ApiSuccess::COMMON_SUCCESS, '密码修改成功');
                } else {
                    return $this->response(ApiError::COMMON_ERROR, '密码修改失败');
                }
            }
        }
    }
}