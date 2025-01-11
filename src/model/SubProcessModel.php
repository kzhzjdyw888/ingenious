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

namespace madong\ingenious\engine\model;

use madong\ingenious\interface\IExecution;
use madong\ingenious\interface\nodes\ISubProcessModel;
use madong\ingenious\libs\traits\DynamicPropsTrait;

/**
 * 子流程模型
 *
 * @author Mr.April
 * @since  1.0
 * @method getVersion()
 * @method getName()
 */
class SubProcessModel extends NodeModel implements ISubProcessModel
{
    use DynamicPropsTrait;

    private string $form;
    private int $version;

    public function exec(IExecution $execution)
    {
        $this->runOutTransition($execution);
    }

}
