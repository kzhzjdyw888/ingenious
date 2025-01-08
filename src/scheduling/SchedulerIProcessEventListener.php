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

namespace madong\plugin\wf\engine\scheduling;


use madong\plugin\wf\engine\core\ServiceContext;
use madong\plugin\wf\engine\event\ProcessEvent;
use madong\plugin\wf\engine\event\IProcessEventListener;
use madong\plugin\wf\engine\libs\utils\Dict;
use madong\plugin\wf\enums\ProcessEventTypeEnum;

class SchedulerIProcessEventListener implements IProcessEventListener
{

    public function onEvent(ProcessEvent $event)
    {
        $schedulerList = ServiceContext::findAll(IScheduler::class);
        if (in_array($event->getEventType(), [
            ProcessEventTypeEnum::PROCESS_INSTANCE_START->value,//流程开始
            ProcessEventTypeEnum::PROCESS_TASK_START->value,//任务开始
        ])) {
            // 流程实例开始事件、流程任务开始事件，添加作业到调度器
            foreach ($schedulerList as $scheduler) {
                $scheduler->addJob($event->getSourceId(),
                    Dict::of([
                        IScheduler::SOURCE_ID_KEY   => $event->getSourceId(),
                        IScheduler::SOURCE_TYPE_KEY => $event->getEventType(),
                    ]));
            }
        } else if (in_array($event->getEventType(), [
            ProcessEventTypeEnum::PROCESS_INSTANCE_END->value,
            ProcessEventTypeEnum::PROCESS_TASK_END->value,
        ])) {
            // 流程实例结束事件、流程任务结束事件，从调度器移除作业
            foreach ($schedulerList as $scheduler) {
                $scheduler->removeJob($event->getSourceId());
            }
        }
    }
}
