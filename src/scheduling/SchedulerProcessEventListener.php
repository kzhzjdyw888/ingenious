<?php
/**
 *+------------------
 * Ingenious
 *+------------------
 * Copyright (c) https://gitee.com/ingenstream/ingenious  All rights reserved. 本版权不可删除，侵权必究
 *+------------------
 * Author: Mr. April (405784684@qq.com)
 *+------------------
 * Software Registration Number: 2024SR0694589
 * Official Website: http://www.ingenstream.cn
 */

namespace ingenious\scheduling;

use ingenious\core\ServiceContext;
use ingenious\enums\ProcessEventTypeEnum;
use ingenious\event\ProcessEvent;
use ingenious\event\ProcessEventListener;
use ingenious\libs\utils\Dict;

class SchedulerProcessEventListener implements ProcessEventListener
{

    public function onEvent(ProcessEvent $event)
    {
        $schedulerList = ServiceContext::findList(IScheduler::class);
        if (in_array($event->getEventType(), [
            ProcessEventTypeEnum::PROCESS_INSTANCE_START[0],//流程开始
            ProcessEventTypeEnum::PROCESS_TASK_START[0],//任务开始
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
            ProcessEventTypeEnum::PROCESS_INSTANCE_END[0],
            ProcessEventTypeEnum::PROCESS_TASK_END[0],
        ])) {
            // 流程实例结束事件、流程任务结束事件，从调度器移除作业
            foreach ($schedulerList as $scheduler) {
                $scheduler->removeJob($event->getSourceId());
            }
        }
    }
}
