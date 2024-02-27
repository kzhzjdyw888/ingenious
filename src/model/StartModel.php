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
use ingenious\enums\ProcessEventTypeEnum;
use ingenious\event\ProcessEvent;
use ingenious\event\ProcessEventService;
use ingenious\event\ProcessPublisher;
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
