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

namespace ingenious\enums;

use ingenious\libs\traits\EnumTrait;

/**
 *
 *
 * @author Mr.April
 * @since  1.0
 */
class ProcessEventTypeEnum implements \ingenious\interface\CodedEnum
{
    use EnumTrait;

    public const PROCESS_INSTANCE_START = [1, "流程实例开始事件"];
    public const PROCESS_INSTANCE_END = [2, "流程实例结束事件"];
    public const PROCESS_TASK_START = [3, "流程任务开始事件"];
    public const PROCESS_TASK_END = [4, "流程任务结束事件"];

}
