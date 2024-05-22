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

use ingenious\enums\ProcessEventTypeEnum;

class ProcessEvent
{
    private string|int $eventType;
    private string|int $sourceId;

    public function __construct($eventType, $sourceId)
    {
        $this->eventType = $eventType;
        $this->sourceId  = $sourceId;
    }

    public function getEventType(): int|string|null
    {
        return $this->eventType ?? null;
    }

    public function getSourceId(): int|string|null
    {
        return $this->sourceId ?? null;
    }

}
