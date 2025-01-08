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

namespace madong\ingenious\core;

use DI\Container;
use Exception;
use madong\ingenious\ex\LFlowException;
use madong\ingenious\interface\IServiceContext;
use madong\ingenious\libs\utils\Logger;
use ReflectionClass;

class ServiceContext implements IServiceContext
{
    protected static ?Container $container = null;
    protected static array $config = [];//扩展配置
    protected static array $backupConfig = []; //备份配置

    public static function setContext(array $context): void
    {
        if (!class_exists(\DI\ContainerBuilder::class)) {
            throw new LFlowException('The "PHP-DI" extension is not installed. Please install it to use the ServiceContext.');
        }

        // 验证上下文的有效性
        if (empty($context)) {
            throw new LFlowException('Context cannot be empty.');
        }

        $builder = new \DI\ContainerBuilder();
        $builder->addDefinitions($context);
        $builder->useAutowiring(true);
        $builder->useAnnotations(false);
        self::$container = $builder->build();
    }

    public static function hasContext(): bool
    {
        return isset(self::$container);
    }

    public static function put(string $name, object $object): void
    {
        self::ensureContextIsSet();
        self::$container->set($name, $object);
    }

    public static function putClass(string $name, string $className): void
    {
        self::ensureContextIsSet();
        self::$container->set($name, \DI\create($className));
    }

    public static function exist(string $name): bool
    {
        return self::hasContext() && self::$container->has($name);
    }

    public static function find(string $clazz): mixed
    {
        return self::hasContext() && self::$container->has($clazz) ? self::$container->get($clazz) : null;
    }

    public static function findAll(string $interfaceName, array $constructorArgs = [], bool $instantiate = true): array
    {
        $instances = [];
        if (!interface_exists($interfaceName)) {
            return [];
        }
        foreach (get_declared_classes() as $class) {
            try {
                $reflectionClass = new ReflectionClass($class);
                if ($reflectionClass->implementsInterface($interfaceName) && $reflectionClass->isInstantiable()) {
                    if ($instantiate) {
                        $instances[] = $reflectionClass->newInstanceArgs($constructorArgs);
                    } else {
                        $instances[] = $class;
                    }
                    continue;
                }
            } catch (Exception $e) {
                Logger::error($e->getMessage());
            }
        }
        return $instances;
    }

    public static function findFirst(string $interfaceName)
    {
        try {
            $implementations = self::findAll($interfaceName);
            return !empty($implementations) ? $implementations[0] : null;
        } catch (\ReflectionException $e) {
            return null;
        }
    }

    public static function findLast(string $interfaceName)
    {
        try {
            $implementations = self::findAll($interfaceName);
            return !empty($implementations) ? end($implementations) : null;
        } catch (\ReflectionException $e) {
            return null;
        }
    }

    public static function register(string $name, mixed $value): void
    {
        self::ensureContextIsSet();

        if (is_string($value)) {
            self::$container->set($name, \DI\create($value));
        } elseif (is_object($value)) {
            self::$container->set($name, $value);
        } else {
            throw new LFlowException('Value must be a class path or an object.');
        }
    }

    public static function registerBatch(array $services): void
    {
        foreach ($services as $name => $value) {
            self::register($name, $value);
        }
    }

    public static function extensions(array $config = []): void
    {
        self::$config = $config;
    }

    public static function getConfig(string $name, mixed $default = null): mixed
    {
        return self::$config[$name] ?? $default;
    }

    public static function setBackupConfig(array $data = []): array
    {
        return self::$backupConfig = $data;
    }

    public static function getBackupConfig(): array
    {
        return self::$backupConfig ?? [];
    }

    private static function ensureContextIsSet(): void
    {
        if (!self::hasContext()) {
            throw new LFlowException('Container is not set. Please set the context first.');
        }
    }

    public static function isDebug(): bool
    {
        return !empty(self::$config['debug']) && self::$config['debug'] === true;
    }
}

