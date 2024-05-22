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

use ingenious\db\ProcessDesign;
use ingenious\db\ProcessType;

interface ProcessDesignServiceInterface
{
    /**
     * 添加流程设计
     *
     * @param object $param
     *
     * @return bool
     */
    public function create(object $param): bool;

    /**
     * 更新流程设计
     *
     * @param object $param
     *
     * @return bool
     */
    public function update(object $param): bool;

    /**
     * 自定义分页查询流程设计
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
     */
    public function findById(string $id): ?ProcessDesign;

    /**
     * 更新流程定义信息
     *
     * @param object $jsonObject
     *
     * @return bool
     */
    public function updateDefine(object $jsonObject): bool;

    /**
     * 流程定义部署
     *
     * @param string     $processDesignId
     * @param string|int $operation
     */
    public function deploy(string $processDesignId, string|int $operation): void;

    /**
     * 重新部署流程定义文件，覆盖最新版本（即不生成新版本）
     */
    public function redeploy(string $processDesignId, string|int $operation): void;

    /**
     * 按流程分类给流程设计分组
     *
     * @return \ingenious\db\ProcessType|null
     */
    public function listByType(): ?ProcessType;
}
