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
 * 任务状态枚举
 *
 * @author Mr.April
 * @since  1.0
 */
enum ProcessTaskStateEnum: int implements IEnum
{
    use EnumTrait;
    case DOING = 10;      // 进行中
    case FINISHED = 20;   // 已完成
    case WITHDRAW = 30;   // 已撤回
    case INTERRUPT = 40;  // 强行终止
    case PENDING = 50;    // 挂起
    case ABANDON = 99;    // 已废弃

    public function label(): string
    {
        return match ($this) {
            self::DOING => '进行中',
            self::FINISHED => '已完成',
            self::WITHDRAW => '已撤回',
            self::INTERRUPT => '强行终止',
            self::PENDING => '挂起',
            self::ABANDON => '已废弃',
        };
    }
}
