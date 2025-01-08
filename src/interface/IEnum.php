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

interface IEnum
{
    public static function all(): array;

    public static function getCode(string $name): mixed;

    public static function getName(int $code): ?string;

    public static function codeOf($codeOrName, $default = null): mixed;

    public static function getEnumValues(): array;
}
