<?php
namespace Base\ConstDir\Api;

class ApiError{
    const LOGIN_FAILED = -100;
    const LOGIN_FAILED_MSG = '登录验证失败';
    const VERIFY_CODE_AUTH_FAILED = -101;
    const VERIFY_CODE_AUTH_FAILED_MSG = '验证码验证失败';
    const NEED_LOGIN = -102;
    const NEED_LOGIN_MSG = '需要登录才能访问';
    const PARAMETER_MISSING = -103;
    const PARAMETER_MISSING_MSG = '参数缺失';
    const DATA_INSERT_FAILED = -104;
    const DATA_INSERT_FAILED_MSG = '数据插入失败';
    const TWICE_PASSWORD_NOT_SIMILAR = -105;
    const TWICE_PASSWORD_NOT_SIMILAR_MSG = '两次输入的密码不一致';
    const VERIFY_CODE_INVALID = -106;
    const VERIFY_CODE_INVALID_MSG = '验证码验证失败';
    const MOBILE_VALIDATE_FAILED = -107;
    const MOBILE_VALIDATE_FAILED_MSG = '手机号格式不正确';
    const DATA_DELETE_FAILED = -108;
    const DATA_DELETE_FAILED_MSG = '数据删除失败';
    const DATA_UPDATE_FAILED = -109;
    const DATA_UPDATE_FAILED_MSG = '数据更新失败';
}