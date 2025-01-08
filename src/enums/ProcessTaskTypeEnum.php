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

enum ProcessTaskTypeEnum: int implements IEnum
{
    use EnumTrait;
    case MAJOR = 0;       // 主办
    case SECONDARY = 1;   // 协办
    case RECORD = 2;      // 记录

    public function label(): string
    {
        return match ($this) {
            self::MAJOR => '主办',
            self::SECONDARY => '协办',
            self::RECORD => '记录',
        };
    }
}
