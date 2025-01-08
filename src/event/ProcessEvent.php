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
