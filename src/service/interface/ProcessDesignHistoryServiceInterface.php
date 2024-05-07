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


use ingenious\db\ProcessDesignHistory;

interface ProcessDesignHistoryServiceInterface
{
    /**
     * 添加流程设计历史
     *
     * @param object $param
     *
     * @return bool
     */
    public function create(object $param): bool;

    /**
     * 更新流程设计历史
     *
     * @param object $param
     *
     * @return bool
     */
    public function update(object $param): bool;

    /**
     * 自定义分页查询流程设计历史
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
     * @return \ingenious\db\ProcessDesignHis
     */
    public function findById(string $id): ?ProcessDesignHistory;

    /**
     * 获取最新的流程设计
     *
     * @param string $processDesignId
     *
     * @return \ingenious\db\ProcessDesignFlow|null
     */
    public function getLatestByProcessDesignId(string $processDesignId): ?ProcessDesignHistory;

}
