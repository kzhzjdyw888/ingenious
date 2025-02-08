<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

namespace phoenix\utils;

use Ramsey\Uuid\Uuid;

/**
 *
 * 字符串操作帮助类
 * @author Mr.April
 * @since  1.0
 */
class Str
{
    /**
     * @param string $action
     * @param string $controller
     * @param string $module
     * @param        $route
     *
     * @return string
     */
    public static function getAuthName(string $action, string $controller, string $module, $route): string
    {
        return strtolower($module . '/' . $controller . '/' . $action . '/' . self::paramStr($route));
    }

    /**
     * @param $params
     *
     * @return string
     */
    public static function paramStr($params): string
    {
        if (!is_array($params)) $params = json_decode($params, true) ?: [];
        $p = [];
        foreach ($params as $key => $param) {
            $p[] = $key;
            $p[] = $param;
        }
        return implode('/', $p);
    }

    /**
     * 截取中文指定字节
     *
     * @param string $str
     * @param int    $utf8len
     * @param string $chaet
     * @param string $file
     *
     * @return string
     */
    public static function substrUTf8(string $str, int $utf8len = 100, string $chaet = 'UTF-8', string $file = '....'): string
    {
        if (mb_strlen($str, $chaet) > $utf8len) {
            $str = mb_substr($str, 0, $utf8len, $chaet) . $file;
        }
        return $str;
    }

    /**
     * 生成字符串id
     *
     * @return string
     */
    public static function uuid(): string
    {
        return Uuid::uuid4()->toString();
    }
}
