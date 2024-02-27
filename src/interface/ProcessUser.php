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

/**
 * 工作流用户信息API
 *
 * @author Mr.April
 * @since  1.0
 */
interface ProcessUser
{

    /**
     * 获取用户名
     *
     * @param string|int $userId
     *
     * @return string
     */
    public function getRealName(string|int $userId): string;

    /**
     * 获取部门id
     *
     * @param string $userId
     *
     * @return string
     */
    public function getDeptId(string $userId): string;

    /**
     * 获取部门名称
     *
     * @param string $userId
     *
     * @return string
     */
    public function getDeptName(string $userId): string;

    /**
     * 获取岗位id
     *
     * @param string $userId
     *
     * @return string
     */
    public function getPostId(string $userId): string;

    /**
     * 获取岗位名称
     *
     * @param string $userId
     *
     * @return string
     */
    public function getPostName(string $userId): string;

}
