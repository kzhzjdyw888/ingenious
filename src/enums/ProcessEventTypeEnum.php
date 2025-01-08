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

namespace madong\ingenious\enums;

use madong\ingenious\interface\IEnum;
use madong\ingenious\libs\traits\EnumTrait;

/**
 * @author Mr.April
 * @since  1.0
 */
enum ProcessEventTypeEnum: int implements IEnum
{

    use EnumTrait;

    case PROCESS_INSTANCE_START = 1;
    case PROCESS_INSTANCE_END = 2;
    case PROCESS_TASK_START = 3;
    case PROCESS_TASK_END = 4;

    public function label(): string
    {
        return match ($this) {
            self::PROCESS_INSTANCE_START => '流程实例开始事件',
            self::PROCESS_INSTANCE_END => '流程实例结束事件',
            self::PROCESS_TASK_START => '流程任务开始事件',
            self::PROCESS_TASK_END => '流程任务结束事件',
        };
    }
}
