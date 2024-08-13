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
