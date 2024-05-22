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
declare (strict_types=1);

namespace ingenious\libs\utils;

use ingenious\ex\LFlowException;

/**
 * 断言帮助类
 *
 * @author Mr.April
 * @since  1.0
 */
abstract class AssertHelper
{

    /**
     * 断言表达式为false
     *
     * @param bool   $expression
     * @param string $message 异常打印信息
     *
     * @throws \ingenious\ex\LFlowException
     */
    public static function isTrue(bool $expression, string $message = "[Assertion failed] - this expression must be true"): void
    {
        if (!$expression) {
            throw new LFlowException($message);
        }
    }

    /**
     * 断言表达式为True
     *
     * @param bool   $expression
     * @param string $message
     */
    public static function notTrue(bool $expression, string $message = "[Assertion failed] - this expression must be true"): void
    {
        if ($expression) {
            throw new LFlowException($message);
        }
    }

    /**
     * 断言给定的object对象为空
     *
     * @param \helper\mixed $object |null $object
     * @param string        $message
     *
     * @throws \ingenious\ex\LFlowException
     */
    public static function isNull(mixed $object, string $message = "[Assertion failed] - the object argument must be null"): void
    {
        if (!empty($object)) {
            throw new LFlowException($message);
        }
    }

    /**
     * 断言给定的object对象为非空
     *
     * @param object|string|null $object
     * @param string             $message
     *
     * @throws ingenious\exceptions\LFlowException
     */
    public static function notNull(mixed $object, string $message = "[Assertion failed] - this argument is required; it must not be null"): void
    {
        if (empty($object)) {
            throw new LFlowException($message);
        }
    }

    /**
     * 断言给定的字符串为非空
     *
     * @param string|null $str
     * @param string      $message
     *
     * @throws ingenious\exceptions\LFlowException
     */
    public static function notEmpty(string|null $str, string $message = "[Assertion failed] - this argument is required; it must not be null or empty"): void
    {
        if ($str == null || strlen($str) == 0) {
            throw new LFlowException($message);
        }
    }
}
