<?php
namespace HaleyLeoZhang\Exceptions;

// ----------------------------------------------------------------------
// 报错编码与反馈信息
// ----------------------------------------------------------------------
// Link  : http://www.hlzblog.top/
// GITHUB: https://github.com/HaleyLeoZhang
// ----------------------------------------------------------------------

class Consts
{
    /**
     * 公共错误码 - 通用型
     */
    const VALIDATE_PARAMS = 1002; // 传入参数不正确

    /**
     * 业务逻辑错误码与对应描述信息配置
     */
    public static $message = [
        /**
         * 公共错误码 - 通用型
         */
        self::VALIDATE_PARAMS => '传入参数不正确',
    ];

}
