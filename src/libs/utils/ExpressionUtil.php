<?php
/**
 *+------------------
 * Ingenious
 *+------------------
 * Copyright (c) https://gitee.com/ingenstream/ingenious  All rights reserved. 本版权不可删除，侵权必究
 *+------------------
 * Author: Mr. April (405784684@qq.com)
 *+------------------
 * Software Registration Number: 2024SR0694589
 * Official Website: http://www.ingenstream.cn
 */

namespace ingenious\libs\utils;

use ingenious\interface\Expression;
use InvalidArgumentException;
use ParseError;
use Throwable;

/**
 *
 * 表达式处理
 * @author Mr.April
 * @since  1.0
 */
class ExpressionUtil implements Expression
{
    public static function eval(string $expr, object|array $args): bool
    {
        if (!is_array($args) && !is_object($args)) {
            throw new InvalidArgumentException('ExpressionUtil Input must be an array or an object.');
        }
        if (is_object($args)) {
            $args = (array)$args;
        }
        extract($args);
        // 确保表达式是有效的
        if (empty($expr)) {
            return false;
        }
        try {
            $result = eval("return ($expr);");
            return (bool)$result;
        } catch (ParseError|Throwable $e) {
            return false;
        }
    }
}
