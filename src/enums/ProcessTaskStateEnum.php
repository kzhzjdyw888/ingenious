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
