<?php
/**
 *+------------------
 * ingenious
 *+------------------
 * Copyright (c) https://gitcode.com/motion-code  All rights reserved.
 *+------------------
 * Author: Mr. April (405784684@qq.com)
 *+------------------
 * Software Registration Number: 2024SR0694589
 * Official Website: https://madong.tech
 */

namespace madong\ingenious\model;

use madong\ingenious\interface\IExecution;
use madong\ingenious\interface\nodes\IJoinModel;
use madong\ingenious\libs\traits\DynamicPropsTrait;
use madong\ingenious\processor\handler\MergeBranchHandler;


/**
 * 合并模型
 *
 * @author Mr.April
 * @since  1.0
 * @method getName()
 */
class JoinModel extends NodeModel implements IJoinModel
{
    use DynamicPropsTrait;

    public function exec(IExecution $execution): void
    {
        $this->fire(new MergeBranchHandler($this), $execution);
        if ($execution->isMerged()) {
            $this->runOutTransition($execution);
        }
    }

}
