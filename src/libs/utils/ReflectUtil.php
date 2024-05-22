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
