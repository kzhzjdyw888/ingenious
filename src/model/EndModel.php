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
use madong\ingenious\interface\nodes\IEndModel;
use madong\ingenious\libs\traits\DynamicPropsTrait;
use madong\ingenious\libs\utils\Logger;
use madong\ingenious\processor\handler\EndProcessHandler;

/**
 * @method getName()
 */
class EndModel extends NodeModel implements IEndModel
{

    use DynamicPropsTrait;

    public function exec(IExecution $execution)
    {
        $sourceId = $execution->getProcessInstanceId() ?? '';
        Logger::debug('endModel:' . ' 执行流程实例ID=' . $sourceId . '节点=' . $this->getName() ?? '');
        // 执行结束节点自定义执行逻辑
        $this->fire(new EndProcessHandler($this), $execution);
    }
}
