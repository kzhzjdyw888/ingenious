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

use ingenious\db\ProcessForm;
use ingenious\db\ProcessType;

interface ProcessFormServiceInterface
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
    public function findById(string $id): ?ProcessForm;

    /**
     * 更新表单义信息
     *
     * @param object $jsonObject
     *
     * @return bool
     */
    public function updateForm(object $jsonObject): bool;

}
