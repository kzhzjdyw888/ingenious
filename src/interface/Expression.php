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

namespace ingenious\interface;

interface Expression
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
