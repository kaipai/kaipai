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
    const PASSWORD_LT_SIX_WORDS = -110;
    const PASSWORD_LT_SIX_WORDS_MSG = '密码小于6位';
    const MEMBER_EXIST_NICK_NAME = -111;
    const MEMBER_EXIST_NICK_NAME_MSG = '该昵称已被使用';
    const MEMBER_EXIST_MOBILE = -112;
    const MEMBER_EXIST_MOBILE_MSG = '该手机号已被使用';
    const REG_PIC_VERIFY_CODE_FAILED = -113;
    const REG_PIC_VERIFY_CODE_FAILED_MSG = '图片验证码错误';
    const ORDER_HAVE_PAID = -114;
    const ORDER_HAVE_PAID_MSG = '该订单已付款';
    const ORDER_TRANSACTION_FAILED = -115;
    const ORDER_TRANSACTION_FAILED_MSG = '订单事务处理失败';

}