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
