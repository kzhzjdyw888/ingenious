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
