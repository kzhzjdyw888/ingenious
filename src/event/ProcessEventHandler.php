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

namespace ingenious\event;

use Exception;
use ingenious\core\ServiceContext;
use ingenious\interface\NotificationMessage;

class ProcessEventHandler
{
    public function handle(ProcessEvent $event): void
    {
        $newsList = ServiceContext::findList(NotificationMessage::class);
        foreach ($newsList as $notify) {
            try {
                $notify->notify($event->getEventType(),$event->getSourceId());
            } catch (Exception $e) {
                // 不做任何处理
            }
        }
    }

}
