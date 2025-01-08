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

use madong\ingenious\interface\IExpression;
use InvalidArgumentException;
use ParseError;
use Throwable;

/**
 *
 * 表达式处理
 * @author Mr.April
 * @since  1.0
 */
class Expression implements IExpression
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
