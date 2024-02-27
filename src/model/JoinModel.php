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
use ingenious\processor\handler\MergeBranchHandler;
use ingenious\libs\traits\DynamicMethodTrait;

/**
 * 合并模型
 *
 * @author Mr.April
 * @since  1.0
 */
class JoinModel extends NodeModel
{
    use DynamicMethodTrait;

    public function exec(Execution $execution): void
    {
        $this->fire(new MergeBranchHandler($this), $execution);
        if ($execution->isMerged()) {
            $this->runOutTransition($execution);
        }
    }

}
