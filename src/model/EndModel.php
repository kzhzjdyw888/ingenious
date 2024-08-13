<?php
/**
 * Copyright (C) 2024 Ingenstream
 * This software is licensed under the Apache-2.0 license.
 * A copy of the license can be found at http://www.apache.org/licenses/LICENSE-2.0
 * Official Website: http://www.ingenstream.cn
 * Author: Mr. April <405784684@qq.com>
 * Project: Ingenious
 * Repository: https://gitee.com/ingenstream/ingenious
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
