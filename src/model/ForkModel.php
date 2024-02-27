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

namespace ingenious\model;

use ingenious\core\Execution;
use ingenious\libs\traits\DynamicMethodTrait;

/**
 * 分支定义-元素
 *
 * @author Mr.April
 * @since  1.0
 */
class ForkModel extends NodeModel
{

    use DynamicMethodTrait;

    public function exec(Execution $execution): void
    {
        $this->runOutTransition($execution);
    }
}
