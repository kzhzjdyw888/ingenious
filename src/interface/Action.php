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

use ingenious\core\Execution;

/**
 * 模型行为
 * @author Mr.April
 * @since  1.0
 */
interface Action
{
    public function execute(Execution $execution): void;
}
