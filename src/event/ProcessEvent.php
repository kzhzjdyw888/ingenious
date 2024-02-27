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

use ingenious\enums\ProcessEventTypeEnum;

class ProcessEvent
{
    private ProcessEventTypeEnum $eventType;
    private string|int $sourceId;

    public function __construct($eventType, $sourceId)
    {
        $this->eventType = $eventType;
        $this->sourceId  = $sourceId;
    }

    public function getEventType(): ?ProcessEventTypeEnum
    {
        return $this->eventType ?? null;
    }

    public function getSourceId(): int|string|null
    {
        return $this->sourceId ?? null;
    }

}
