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

use ReflectionClass;
use ReflectionUnionType;


/**
 * 动态地添加getter和setter方法来获取和设置私有属性
 *
 * @author Mr.April
 * @since  1.0
 */
trait DynamicPropsTrait
{
    public function __call(string $name, array $arguments)
    {
        $propertyName = lcfirst(substr($name, 3));

        if (str_starts_with($name, 'get')) {
            return $this->handleGet($propertyName);
        } elseif (str_starts_with($name, 'set')) {
            $this->handleSet($propertyName, $arguments[0]);
        }
    }

    private function handleGet(string $propertyName): mixed
    {
        // 检查属性是否存在，并获取值
        if ($this->propertyExists($propertyName)) {
            return $this->$propertyName ?? $this->defaultValue($this->getPropertyType($propertyName));
        }

        $underscoreName = $this->camelCaseToUnderscore($propertyName);
        if ($this->propertyExists($underscoreName)) {
            return $this->$underscoreName ?? $this->defaultValue($this->getPropertyType($underscoreName));
        }

        return null; // 属性不存在
    }

    private function handleSet(string $propertyName, mixed $value): void
    {
        // 检查属性是否存在，并设置值
        if ($this->propertyExists($propertyName)) {
            $this->$propertyName = $value;
        } elseif ($this->propertyExists($this->camelCaseToUnderscore($propertyName))) {
            $this->{$this->camelCaseToUnderscore($propertyName)} = $value;
        }
    }

    private function propertyExists(string $propertyName): bool
    {
        return property_exists($this, $propertyName);
    }

    private function camelCaseToUnderscore(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    /**
     * 获取属性类型
     *
     * @param string $propertyName
     * @return mixed|null
     * @throws ReflectionException
     */
    private function getPropertyType(string $propertyName): mixed
    {
        $reflection = new ReflectionClass($this);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $type = $property->getType();
        if ($type !== null) {
            return $type instanceof ReflectionUnionType
                ? implode(',', array_map(fn($t) => $t->getName(), $type->getTypes()))
                : $type->getName();
        }
        return null;
    }

    /**
     * 获取默认值
     *
     * @param string $type
     * @return mixed
     */
    private function defaultValue(string $type): mixed
    {
        return match ($type) {
            'string' => '',
            'array' => [],
            'object' => (object)[],
            default => null,
        };
    }
}
