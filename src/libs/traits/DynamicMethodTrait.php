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
                return $this->$propertyName;
            } elseif (property_exists($this, $this->camelCaseToUnderscore($propertyName))) {
                return $this->{$this->camelCaseToUnderscore($propertyName)};
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

}
