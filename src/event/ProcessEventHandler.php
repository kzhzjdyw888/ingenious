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

namespace ingenious\event;

use Exception;
use ingenious\core\ServiceContext;
use ingenious\interface\NotificationMessage;

class ProcessEventHandler
{
    public function handle(ProcessEvent $event): void
    {
        $processEventListenerList = ServiceContext::findList(ProcessEventListener::class);
        foreach ($processEventListenerList as $processEventListener) {
            $processEventListener->onEvent($event);
        }
    }

}
