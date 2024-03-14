<?php

/**
 *+------------------
 * Lflow
 *+------------------
 * Copyright (c) 2023~2030 gitee.com/liu_guan_qing All rights reserved.本版权不可删除，侵权必究
 *+------------------
 * Author: Mr.April(405784684@qq.com)
 *+------------------
 */

namespace ingenious\libs\utils;

use ingenious\ex\LFlowException;

/**
 * 字符串处理帮助类
 *
 * @author Mr.April
 * @since  1.0
 */
class StringHelper
{
    /**
     * 获取uuid类型的字符串
     *
     * @param string $namespace
     *
     * @return string
     */
    public static function getPrimaryKey(string $namespace = ''): string
    {

        $uid  = uniqid("", true);
        $data = $namespace;
        $data .= $_SERVER['REQUEST_TIME'];//请求开始的时间戳
        $data .= $_SERVER['HTTP_USER_AGENT'];//当前请求的 User-Agent: 头部的内容。
        $data .= $_SERVER['REMOTE_ADDR'];//正在浏览当前页面用户的 IP 地址
        $data .= $_SERVER['REMOTE_PORT'];//用户连接到服务器时所使用的端口
        $hash = hash('ripemd128', $uid . md5($data));
        return substr($hash, 0, 8) . '-' . substr($hash, 8, 4) . '-' . substr($hash, 12, 4) . '-' . substr($hash, 16, 4) . '-' . substr($hash, 20, 12);
    }

    /**
     * 判断字符串是否为空
     *
     * @param string|null $str
     *
     * @return bool
     */
    public static function isEmpty(string|null $str): bool
    {
        return empty($str);
    }

    /**
     * 判断字符串是否为非空
     *
     * @param string|null $str
     *
     * @return bool
     */
    public static function isNotEmpty(mixed $str): bool
    {
        return !empty($str);
    }

    /**
     * 字符串比较
     *
     * @param string|int|null $str1
     * @param string|int|null $str2
     *
     * @return bool
     */
    public static function equalsIgnoreCase(string|null|int $str1, string|null|int $str2): bool
    {
        return strcasecmp((string)$str1, (string)$str2) === 0;
    }

    /**
     * 构造排序条件
     *
     * @param string|null $order
     * @param string|null $orderBy
     *
     * @return string
     */
    public static function buildPageOrder(string|null $order, string|null $orderBy): string
    {
        if (empty($order) || empty($orderBy)) {
            return "";
        }
        $orderByArray = explode(',', $orderBy);
        $orderArray   = explode(',', $order);
        if (count($orderArray) != count($orderByArray)) {
            throw new LFlowException("分页多重排序参数中,排序字段与排序方向的个数不相等");
        }
        $orderStr = " order by ";
        for ($i = 0; $i < count($orderByArray); $i++) {
            $orderStr .= $orderByArray[$i] . " " . $orderArray[$i] . " ,";
        }
        return substr($orderStr, 0, strlen($orderStr) - 1);
    }

    /**
     * 标识符存在截取后面的内容不存在返回全部
     * @param string $str
     * @param string $tag
     *
     * @return string
     */
    public static function substringAfterColon(string $str, string $tag = ":"):string
    {
        $pos = strpos($str,  $tag);
        if ($pos !== false) {
            return substr($str, $pos + 1);
        } else {
            return $str;
        }
    }
}
