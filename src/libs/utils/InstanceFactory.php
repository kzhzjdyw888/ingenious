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

namespace madong\ingenious\libs\utils;

use InvalidArgumentException;
use ReflectionClass;


class InstanceFactory
{
    private array $reflectionCache = [];

    public function newInstance(string $className, array $constructorArgs = []): ?object
    {
        if (!class_exists($className)) {
            throw new InvalidArgumentException("Class '{$className}' does not exist.");
        }
        if (!isset($this->reflectionCache[$className])) {
            $this->reflectionCache[$className] = new ReflectionClass($className);
        }
        try {
            return $this->reflectionCache[$className]->newInstanceArgs($constructorArgs);
        } catch (\ReflectionException $e) {
            return null;
        }
    }
}

