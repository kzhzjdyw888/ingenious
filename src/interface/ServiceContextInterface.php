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

interface ServiceContextInterface {
    public static function setContext($context = []): void;
    public static function put($name, $object): void;
    public static function putClass($name, $clazz): void;
    public static function exist($name): bool;
    public static function find($clazz);
    public static function findList($clazz): array;
    public static function findByName($name, $clazz);
    public static function findAll(string $interfaceName): array;
}
