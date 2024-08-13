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
 * 任务状态枚举
 *
 * @author Mr.April
 * @since  1.0
 */
class ProcessTaskStateEnum   implements \ingenious\interface\CodedEnum
{
    use EnumTrait;

    public const DOING = [10, "进行中"];
    public const FINISHED = [20, "已完成"];
    public const WITHDRAW = [30, "已撤回"];
    public const INTERRUPT = [40, "强行终止"];
    public const PENDING = [50, "挂起"];
    public const ABANDON = [99, "已废弃"];

}
