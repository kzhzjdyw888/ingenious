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
