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
