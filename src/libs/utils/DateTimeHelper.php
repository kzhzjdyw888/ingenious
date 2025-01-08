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

use DateTime;

class DateTimeHelper
{

    /**
     * 将时间戳转换为字符串日期
     *
     * @param int|string $timestamp 时间戳
     * @param string     $format    日期格式，默认为 Y-m-d H:i:s
     *
     * @return string 格式化后的日期字符串
     */
    public static function timestampToString(int|string $timestamp, string $format = 'Y-m-d H:i:s'): string
    {
        if (empty($timestamp)) {
            return '';
        }
        return date($format, $timestamp);
    }

    /**
     * 将日期时间字符串转换为时间戳
     *
     * @param string      $dateTimeStr 日期时间字符串
     * @param string|null $format      日期时间格式，可选。默认为 null，将使用 strtotime() 进行解析
     *
     * @return int|false 时间戳，如果解析失败则返回 false
     */
    public static function dateTimeStringToTimestamp(string $dateTimeStr, string $format = null): bool|int
    {
        if ($format === null) {
            // 不提供格式，使用 strtotime() 尝试解析
            $timestamp = strtotime($dateTimeStr);
            // 检查 strtotime() 是否成功解析了日期时间字符串
            if ($timestamp === false) {
                // 解析失败，返回 false
                return false;
            }
            // 返回解析成功的时间戳
            return $timestamp;
        } else {
            // 提供了格式，使用 DateTime::createFromFormat() 解析
            $dateTime = DateTime::createFromFormat($format, $dateTimeStr);
            if ($dateTime === false) {
                // 解析失败，返回 false
                return false;
            }
            // 返回解析成功的时间戳
            return $dateTime->getTimestamp();
        }
    }

    /**
     * 判断是否8601
     *
     * @param $dateString
     *
     * @return bool
     */
    public static function isValidISO8601($dateString): bool
    {
        $regex = '/^(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\.\d{1,3})?Z)$/';
        return preg_match($regex, $dateString) === 1;
    }

    public static function convertToTimestamp($time)
    {
        return DateTimeHelper::isValidISO8601($time) || is_string($time) ? DateTimeHelper::dateTimeStringToTimestamp($time) : $time;
    }
}
