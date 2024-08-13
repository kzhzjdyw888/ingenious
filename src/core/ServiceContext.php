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

namespace ingenious\core;

use ingenious\ex\LFlowException;
use ingenious\interface\ServiceContextInterface;

class ServiceContext implements ServiceContextInterface
{

    private function __construct()
    {
        // 私有构造函数，防止外部实例化
    }

    private static array $context = [];

    public static function setContext($context = []): void
    {
        self::$context = $context;
    }

    public static function put($name, $object): void
    {
        if (!isset(self::$context)) {
            throw new LFlowException("未注册服务上下文");
        }
        self::$context[$name] = $object;
    }

    public static function putClass($name, $clazz): void
    {
        if (!isset(self::$context)) {
            throw new LFlowException("未注册服务上下文");
        }
        self::$context[$name] = new $clazz();
    }

    public static function exist($name): bool
    {
        if (!isset(self::$context)) {
            throw new LFlowException("未注册服务上下文");
        }
        return isset(self::$context[$name]);
    }

    public static function find($clazz)
    {
        if (!isset(self::$context)) {
            throw new LFlowException("未注册服务上下文");
        }
        return self::$context[$clazz];
    }

    public static function findList($clazz): array
    {
        if (!isset(self::$context)) {
            throw new LFlowException("未注册服务上下文");
        }
        $result = [];
        foreach (self::$context as $name => $object) {
            if ($object instanceof $clazz) {
                $result[] = $object;
            }
        }
        return $result;
    }

    public static function findByName($name, $clazz)
    {
        if (!isset(self::$context)) {
            throw new LFlowException("未注册服务上下文");
        }
        $object = self::$context[$name];
        if ($object instanceof $clazz) {
            return $object;
        }
        return null;
    }

    public static function findAll(string $interfaceName): array
    {
        $services = [];
        foreach (self::$context as $service) {
            if ($service instanceof $interfaceName) {
                $services[] = $service;
            }
        }
        return $services;
    }

    public static function findFirst(string $interfaceName): ?object
    {
        foreach (self::$context as $service) {
            if ($service instanceof $interfaceName) {
                return $service;
            }
        }
        return null; // 如果没有找到实现该接口的对象，则返回 null
    }
}

