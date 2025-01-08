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


use madong\ingenious\event\ProcessEventService;
use madong\ingenious\interface\IExecution;
use madong\ingenious\interface\nodes\IStartModel;
use madong\ingenious\libs\traits\DynamicPropsTrait;
use madong\ingenious\libs\utils\Logger;
use madong\ingenious\enums\ProcessEventTypeEnum;

/**
 * 开始-模型
 *
 * @author Mr.April
 * @since  1.0
 * @method getName()
 */
class StartModel extends NodeModel implements IStartModel
{
    use DynamicPropsTrait;

    public function exec(IExecution $execution): void
    {
        //执行开始节点逻辑
        $sourceId = $execution->getProcessInstanceId();
        Logger::debug('starModel:' . ' 执行流程实例ID=' . $sourceId . '节点=' . $this->getName() ?? '');

        //执行开始节点事件
        ProcessEventService::publishNotification(ProcessEventTypeEnum::PROCESS_INSTANCE_START->value, $execution->getProcessInstanceId());
        $this->runOutTransition($execution);
    }

}
