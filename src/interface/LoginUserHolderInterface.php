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
 * 获取系统登入用户
 *
 * @author Mr.April
 * @since  1.0
 */
interface LoginUserHolderInterface
{

    public function getUserId(): string|int;

    public function getUserName(): string;

    public function getRealName(): string;
}