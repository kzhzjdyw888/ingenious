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

namespace madong\ingenious\libs\traits;

trait EnumTrait
{
    public static function getCode(string $name): mixed
    {
        foreach (static::cases() as $enum) {
            if ($enum->name === $name) {
                return $enum->value;
            }
        }
        return null;
    }

    public static function getName(int $code): ?string
    {
        foreach (static::cases() as $enum) {
            if ($enum->value === $code) {
                return $enum->name;
            }
        }
        return null;
    }

    public static function codeOf($codeOrName, $default = null): mixed
    {
        foreach (static::cases() as $enum) {
            if ($enum->value === $codeOrName || $enum->name === $codeOrName) {
//                return $enum;
                return $enum->value;
            }
        }
        return $default !== null ? $default : static::cases()[0];
    }

    public static function getEnumValues(): array
    {
        return array_map(fn($case) => $case->value, static::cases());
    }

    public static function getValueByLabel(string $label): string|int|null
    {
        foreach (self::cases() as $case) {
            if ($case->label() === $label) {
                return $case->value;
            }
        }
        return null;
    }

    public static function all(): array
    {
        return static::cases();
    }

}
