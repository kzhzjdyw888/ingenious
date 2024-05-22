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


use ingenious\db\ProcessTaskActor;

/**
 * 流程任务和参与人服务类
 *
 * @author Mr.April
 * @since  1.0
 */
interface ProcessTaskActorServiceInterface
{
        /**
     * 添加流程任务和参与人关系
     *
     * @param object $param
     *
     * @return bool
     */
    public function create(object $param): bool;

    /**
     * 更新流程任务和参与人关系
     *
     * @param object $param
     *
     * @return bool
     */
    public function update(object $param): bool;

    /**
     * 自定义分页查询流程任务和参与人关系
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
     * @return \ingenious\db\ProcessTaskActor|null
     */
    public function findById(string $id): ?ProcessTaskActor;

}
