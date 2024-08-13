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

namespace ingenious\libs\utils;

use ReflectionClass;
use ReflectionProperty;

class ReflectUtil
{
    /**
     * 使用反射实例化类
     *
     * @param string $className       类名
     * @param array  $constructorArgs 构造函数的参数
     *
     * @return object|null 实例化后的对象
     */
    public static function newInstance(string $className, array $constructorArgs = []): ?object
    {
        try {
            $reflectionClass = new ReflectionClass($className);
            return $reflectionClass->newInstanceArgs($constructorArgs);
        } catch (\ReflectionException $e) {
            return null;
        }
    }

}
