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
use ingenious\libs\utils\Logger;
use ingenious\processor\handler\EndProcessHandler;

class EndModel extends NodeModel
{

    use DynamicMethodTrait;

    public function exec(Execution $execution)
    {
        $sourceId = $execution->getProcessInstanceId() ?? '';
        Logger::debug('endModel:' . ' 执行流程实例ID=' . $sourceId . '节点=' . $this->getName() ?? '');
        // 执行结束节点自定义执行逻辑
        $this->fire(new EndProcessHandler($this), $execution);
    }

}
