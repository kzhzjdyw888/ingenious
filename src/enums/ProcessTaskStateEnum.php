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
