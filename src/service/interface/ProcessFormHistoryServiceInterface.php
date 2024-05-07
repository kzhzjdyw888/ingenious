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

namespace ingenious\service\interface;

use ingenious\db\ProcessFormHistory;

interface ProcessFormHistoryServiceInterface
{
    /**
     * 添加设计历史
     *
     * @param object $param
     *
     * @return bool
     */
    public function create(object $param): bool;

    /**
     * 更新设计历史
     *
     * @param object $param
     *
     * @return bool
     */
    public function update(object $param): bool;

    /**
     * 自定义分页查询流程历史
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
     * @return \ingenious\db\ProcessFormBuilder
     */
    public function findById(string $id): ?ProcessFormHistory;

    /**
     * 获取最新的设计
     *
     * @param string $processDesignId
     *
     * @return \ingenious\db\ProcessFormHistory|null
     */
    public function getLatestByProcessDesignId(string $processDesignId): mixed;

}
