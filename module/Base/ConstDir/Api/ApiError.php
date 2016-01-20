<?php
namespace Base\ConstDir\Api;

class ApiError{
    const LOGIN_FAILED = -100;
    const LOGIN_FAILED_MSG = '登录验证失败';
    const VERIFY_CODE_AUTH_FAILED = -101;
    const VERIFY_CODE_AUTH_FAILED_MSG = '验证码验证失败';
    const NEED_LOGIN = -102;
    const NEED_LOGIN_MSG = '需要登录才能访问';
}