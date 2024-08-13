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
