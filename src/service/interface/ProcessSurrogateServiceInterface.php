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

namespace ingenious\service\interface;


use ingenious\db\ProcessSurrogate;

interface ProcessSurrogateServiceInterface
{

    /**
     * 添加流程委托代理
     *
     * @param object $param
     *
     * @return bool
     */
    public function create(object $param): bool;

    /**
     * 更新流程委托代理
     *
     * @param object $param
     *
     * @return bool
     */
    public function update(object $param): bool;

    /**
     * 自定义分页查询流程委托代理
     *
     * @param object $param
     *
     * @return array
     */
    public function page(object $param): array;

    /**
     * 通过id查询
     *
     * @param string $id
     *
     * @return \ingenious\db\ProcessSurrogate|null
     */
    public function findById(string $id): ?ProcessSurrogate;

    /**
     * 获取代理人
     *
     * @param string $operator
     * @param string $processName
     *
     * @return string|null
     */
    public function getSurrogate(string $operator,string $processName):?string;

}
