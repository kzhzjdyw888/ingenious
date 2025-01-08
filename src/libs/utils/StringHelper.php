<?php
/**
 *+------------------
 * ingenious
 *+------------------
 * Copyright (c) https://gitcode.com/motion-code  All rights reserved.
 *+------------------
 * Author: Mr. April (405784684@qq.com)
 *+------------------
 * Software Registration Number: 2024SR0694589
 * Official Website: https://madong.tech
 */

namespace madong\ingenious\libs\utils;

use madong\ingenious\ex\LFlowException;

class StringHelper
{
    /**
     * 获取uuid类型的字符串
     *
     * @param string $namespace
     * @return string
     */
    public static function getPrimaryKey(string $namespace = ''): string
    {
        $uid = uniqid("", true);
        $data = $namespace
            . $_SERVER['REQUEST_TIME'] ?? ''
            . $_SERVER['HTTP_USER_AGENT'] ?? ''
            . $_SERVER['REMOTE_ADDR'] ?? ''
            . $_SERVER['REMOTE_PORT'] ?? '';

        $hash = hash('ripemd128', $uid . md5($data));
        return vsprintf('%s-%s-%s-%s-%s', str_split($hash, [8, 4, 4, 4, 12]));
    }

    /**
     * 判断字符串是否为空
     *
     * @param string|null $str
     * @return bool
     */
    public static function isEmpty(?string $str): bool
    {
        return empty($str);
    }

    /**
     * 判断字符串是否为非空
     *
     * @param string|null $str
     * @return bool
     */
    public static function isNotEmpty(?string $str): bool
    {
        return !empty($str);
    }

    /**
     * 字符串比较（忽略大小写）
     *
     * @param string|int|null $str1
     * @param string|int|null $str2
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
     * @return string
     * @throws LFlowException
     */
    public static function buildPageOrder(?string $order, ?string $orderBy): string
    {
        if (empty($order) || empty($orderBy)) {
            return '';
        }

        $orderByArray = explode(',', $orderBy);
        $orderArray = explode(',', $order);

        if (count($orderArray) !== count($orderByArray)) {
            throw new LFlowException("分页多重排序参数中,排序字段与排序方向的个数不相等");
        }

        return 'ORDER BY ' . implode(', ', array_map(fn($field, $direction) => "$field $direction", $orderByArray, $orderArray));
    }

    /**
     * 标识符存在截取后面的内容不存在返回全部
     *
     * @param string $str
     * @param string $tag
     * @return string
     */
    public static function substringAfterColon(string $str, string $tag = ":"): string
    {
        $pos = strpos($str, $tag);
        return $pos !== false ? substr($str, $pos + 1) : $str;
    }
}
