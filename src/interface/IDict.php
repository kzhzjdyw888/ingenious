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

namespace madong\ingenious\interface;

/**
 * 字典接口
 * @author Mr.April
 * @since  1.0
 */
interface IDict
{
    /**
     * 添加一个键值对
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function put(string $key, mixed $value): void;

    /**
     * 添加多个键值对
     *
     * @param array|object $entries
     * @return void
     */
    public function putAll(array|object $entries): void;

    /**
     * 获取指定键的值
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * 移除指定键的键值对
     *
     * @param string $key
     * @return void
     */
    public function remove(string $key): void;

    /**
     * 清空字典
     *
     * @return void
     */
    public function clear(): void;

    /**
     * 将字典转换为数组
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * 获取所有键值对作为 stdClass 对象
     *
     * @return \stdClass
     */
    public function getAll(): \stdClass;

    /**
     * 检查字典是否包含指定键
     *
     * @param string $key
     * @return bool
     */
    public function containsKey(string $key): bool;

    /**
     * 静态方法用于创建字典实例
     *
     * @param array $entries
     * @return static
     */
    public static function of(array $entries): self;
}
