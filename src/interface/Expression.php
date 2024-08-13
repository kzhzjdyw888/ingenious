<?php
/**
 * Copyright (C) 2024 Ingenstream
 * This software is licensed under the Apache-2.0 license.
 * A copy of the license can be found at http://www.apache.org/licenses/LICENSE-2.0
 * Official Website: http://www.ingenstream.cn
 * Author: Mr. April <405784684@qq.com>
 * Project: Ingenious
 * Repository: https://gitee.com/ingenstream/ingenious
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
