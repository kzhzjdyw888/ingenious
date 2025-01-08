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
use madong\ingenious\interface\nodes\IForkModel;
use madong\ingenious\libs\traits\DynamicPropsTrait;

/**
 * 分支定义-元素
 *
 * @author Mr.April
 * @since  1.0
 */
class ForkModel extends NodeModel implements IForkModel
{

    use DynamicPropsTrait;

    public function exec(IExecution $execution): void
    {
        $this->runOutTransition($execution);
    }
}
