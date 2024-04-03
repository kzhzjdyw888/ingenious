<?php
/**
 *+------------------
 * Lflow
 *+------------------
 * Copyright (c) 2023~2030 gitee.com/liu_guan_qing All rights reserved.本版权不可删除，侵权必究
 *+------------------
 * Author: Mr.April(405784684@qq.com)
 *+------------------
 */

namespace ingenious\libs\traits;

use ReflectionClass;
use ReflectionUnionType;

/**
 * 动态地添加getter和setter方法来获取和设置私有属性
 *
 * @author Mr.April
 * @since  1.0
 */
trait DynamicMethodTrait
{

    public function __call($name, $arguments)
    {
        // 检查方法名是否以 "get" 开头
        if (str_starts_with($name, 'get')) {
            // 提取属性名
            $propertyName = lcfirst(substr($name, 3));

            // 检查属性是否存在，并根据属性名的不同形式进行获取
            if (property_exists($this, $propertyName)) {
                $defaultValue = $this->defaultValue($this->getPropertyType($propertyName));
                return $this->$propertyName ?? $defaultValue;
            } elseif (property_exists($this, $this->camelCaseToUnderscore($propertyName))) {
                $defaultValue = $this->defaultValue($this->getPropertyType($this->camelCaseToUnderscore($propertyName)));
                return $this->{$this->camelCaseToUnderscore($propertyName)} ?? $defaultValue;
            }
        } elseif (str_starts_with($name, 'set')) { // 检查方法名是否以 "set" 开头
            // 提取属性名
            $propertyName = lcfirst(substr($name, 3));

            // 检查属性是否存在，并根据属性名的不同形式进行设置
            if (property_exists($this, $propertyName)) {
                $this->{$propertyName} = $arguments[0];
            } elseif (property_exists($this, $this->camelCaseToUnderscore($propertyName))) {
                $this->{$this->camelCaseToUnderscore($propertyName)} = $arguments[0];
            }
        }
    }

    private function camelCaseToUnderscore($input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    /**
     * 类型获取
     *
     * @param $propertyName
     *
     * @return mixed|null
     */
    private function getPropertyType($propertyName)
    {
        $reflection = new ReflectionClass($this);
        $property   = $reflection->getProperty($propertyName);
        $property->setAccessible(true); // 设置属性为可访问，以便获取类型信息
        // 获取属性的类型
        $type = $property->getType();
        if ($type !== null) {
            // 如果类型是联合类型，则获取所有可能的类型名称
            if ($type instanceof ReflectionUnionType) {
                $typeNames = [];
                foreach ($type->getTypes() as $subType) {
                    $typeNames[] = $subType->getName();
                }
                return implode(',', $typeNames);
            } else {
                return $type->getName();
            }
        }
        return null;
    }

    /**
     * 默认值
     *
     * @param string                            $type
     * @param \ingenious\libs\traits\mixed|null $default
     *
     * @return object|string|null
     */
    private function defaultValue(string $type, mixed $default = null)
    {
        switch ($type) {
            case 'string':
                $default = '';
                break;
            case 'array':
                $default = [];
            case 'object':
                $default = (object)[];
                break;
            default:
                $default = null;
                break;
        }
        return $default;
    }

}
