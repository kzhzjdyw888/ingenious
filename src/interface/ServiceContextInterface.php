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
