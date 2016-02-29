<?php
namespace Base\ConstDir\Admin;

class AdminError{
    const PARAMETER_MISSING = -100;
    const PARAMETER_MISSING_MSG = '参数缺失';
    const PASSWD_LENGTH_LT_SIX = -101;
    const PASSWD_LENGTH_LT_SIX_MSG = '密码长度至少6位';
    const DATA_INSERT_FAILED = -102;
    const DATA_INSERT_FAILED_MSG = '数据插入失败';
    const DATA_DELETE_FAILED = -103;
    const DATA_DELETE_FAILED_MSG = '数据删除失败';
    const DATA_UPDATE_FAILED = -104;
    const DATA_UPDATE_FAILED_MSG = '数据更新失败';

}