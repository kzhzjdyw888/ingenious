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


use ingenious\db\ProcessDefineFavorite;

/**
 * 流程定义收藏服务
 *
 * @author Mr.April
 * @since  1.0
 */
interface ProcessDefineFavoriteServiceInterface
{
    /**
     * 添加流程定义收藏
     *
     * @param object $param
     *
     * @return bool
     */
    public function save(object $param): bool;

    /**
     * 更新流程定义收藏
     *
     * @param object $param
     *
     * @return bool
     */
    public function update(object $param): bool;

    /**
     * 自定义分页查询流程定义收藏
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
     * @return \ingenious\db\ProcessDefineFavorite|null
     */
    public function findById(string $id): ?ProcessDefineFavorite;

}