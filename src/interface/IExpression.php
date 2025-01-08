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

namespace madong\ingenious\interface;

interface IExpression
{

    /**
     * 引擎表达式
     *
     * @param string       $expr
     * @param object|array $args
     *
     * @return bool
     */
    public static function eval(string $expr, object|array $args): bool;

}
