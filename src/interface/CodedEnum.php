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

/**
 * 枚举
 * @author Mr.April
 * @since  1.0
 */
interface CodedEnum
{
    public static function getCode($name): mixed;

    public static function getName($code): mixed;

    public static function codeOf($codeOrName, $default = null): mixed;

}
