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

interface IServiceContext
{
    public static function setContext(array $context): void;

    public static function put(string $name, object $object): void;

    public static function putClass(string $name, string $className): void;

    public static function exist(string $name): bool;

    public static function find(string $clazz): mixed;

    public static function findAll(string $interfaceName): array;

    public static function register(string $name, mixed $value): void;

    public static function registerBatch(array $services): void;
}
