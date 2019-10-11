<?php
namespace HaleyLeoZhang\Helpers;

// ----------------------------------------------------------------------
// 公共工具类
// ----------------------------------------------------------------------
// Link  : http://www.hlzblog.top/
// GITHUB: https://github.com/HaleyLeoZhang
// ----------------------------------------------------------------------

use HaleyLeoZhang\Exceptions\ApiException;
use HaleyLeoZhang\Exceptions\Consts;

class Filter
{
    private static $__form = []; // 存储数据

    /**
     * 获取刚刚过滤后的参数
     */
    public static function get_filter_data()
    {
        $data         = self::$__form;
        self::$__form = [];
        return $data;
    }

    /**
     * 验证数组中的数据
     *
     * @param array $param 待验证的数组
     * @param array $conf 验证规则,枚举如下
     * [
     *     'field'   => 'name', // 字段名
     *     'type'   => 'int', // 字段类型 int 或者 string
     *     'max'     => 255, // int 允许最大[值/长度]
     *     'min'     => 0, // int 允许最小[值/长度]
     *     'require' => true, // bool 是否必填  true -> 是 false, 否
     * ];
     * @return void
     */
    public static function request($param, $conf)
    {
        $value = '';
        $field = $conf['field'];
        $type  = $conf['type'];
        // Require
        if (isset($conf['require'])) {
            if (true === $conf['require']) {
                if (!isset($param[$field])) {
                    throw new ApiException("需要字段 {$field}", Consts::VALIDATE_PARAMS);
                }
            }
        }
        if (isset($param[$field])) {
            $value = $param[$field];
        } elseif (isset($conf['default'])) {
            $value = $conf['default'];
        }
        // Length
        if ('int' == $type) {
            $value    = (int) $value;
            $compare  = $value;
            $descript = '值';
        } else {
            $compare  = mb_strlen($value);
            $descript = '长度';
        }

        $len = mb_strlen($value);

        if (isset($conf['min'])) {
            if ($compare < $conf['min']) {
                throw new ApiException("{$field} {$descript}小于 {$conf['min']}", Consts::VALIDATE_PARAMS);
            }
        }
        if (isset($conf['max'])) {
            if ($compare > $conf['max']) {
                throw new ApiException("{$field} {$descript}大于 {$conf['max']}", Consts::VALIDATE_PARAMS);
            }
        }
        self::$__form[$field] = $value;
    }
}
