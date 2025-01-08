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

namespace madong\ingenious\event;


use madong\ingenious\core\ServiceContext;


class ProcessEventHandler
{
    public function handle(ProcessEvent $event): void
    {
        $processEventListenerList = ServiceContext::findAll(IProcessEventListener::class);
        foreach ($processEventListenerList as $processEventListener) {
            $processEventListener->onEvent($event);
        }
    }

}
