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
use ingenious\enums\ProcessEventTypeEnum;
use ingenious\event\ProcessEvent;
use ingenious\event\ProcessEventService;
use ingenious\libs\traits\DynamicMethodTrait;
use ingenious\libs\utils\Logger;

/**
 * 开始-模型
 *
 * @author Mr.April
 * @since  1.0
 * @method getName()
 */
class StartModel extends NodeModel
{
    use DynamicMethodTrait;

    public function exec(Execution $execution): void
    {
        //执行开始节点逻辑
        $sourceId = $execution->getProcessInstanceId();
        Logger::debug('starModel:' . ' 执行流程实例ID=' . $sourceId . '节点=' . $this->getName() ?? '');

        //执行开始节点事件
        ProcessEventService::publishNotification(ProcessEventTypeEnum::PROCESS_INSTANCE_START[0], $execution->getProcessInstanceId());
        $this->runOutTransition($execution);
    }

}
