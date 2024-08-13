<?php
/**
 * Copyright (C) 2024 Ingenstream
 * This software is licensed under the Apache-2.0 license.
 * A copy of the license can be found at http://www.apache.org/licenses/LICENSE-2.0
 * Official Website: http://www.ingenstream.cn
 * Author: Mr. April <405784684@qq.com>
 * Project: Ingenious
 * Repository: https://gitee.com/ingenstream/ingenious
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
