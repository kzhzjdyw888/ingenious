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

namespace ingenious\libs\traits;

use ReflectionClass;

/**
 * 枚举实现类
 * @author Mr.April
 * @since  1.0
 */
trait EnumTrait
{
    protected static array $enumValues = [];

    public static function getCode($name): mixed
    {
        foreach (static::getEnumValues() as $enum) {
            if ($enum[1] === $name) {
                return $enum[0];
            }
        }
        return null;
    }

    public static function getName($code): mixed
    {
        foreach (static::getEnumValues() as $enum) {
            if ($enum[0] === $code) {
                return $enum[1];
            }
        }
        return null;
    }

    public static function codeOf($codeOrName, $default = null): mixed
    {
        foreach (static::getEnumValues() as $enum) {
            if ($enum[0] === $codeOrName || $enum[1] === $codeOrName) {
                return $enum;
            }
        }
        return $default !== null ? $default : static::getEnumValues()[0];
    }

    public static function getEnumValues(): array
    {
        if (empty(static::$enumValues)) {
            $reflection = new ReflectionClass(static::class);
            $constants = $reflection->getConstants();
            static::$enumValues = array_values($constants);
        }
        return static::$enumValues;
    }
}
