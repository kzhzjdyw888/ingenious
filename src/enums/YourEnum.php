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

enum YourEnum:int implements IEnum
{

    use EnumTrait;
    case NO = 0;
    case YES = 1;

    public function label(): string
    {
        return match ($this) {
            self::NO => '未读',
            self::YES => '已读',
        };
    }
}
