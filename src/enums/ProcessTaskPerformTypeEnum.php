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

enum ProcessTaskPerformTypeEnum: int implements IEnum
{
    use EnumTrait;

    case NORMAL = 0;                     // 普通参与
    case COUNTERSIGN = 1;                     // 会签参与

    public function label(): string
    {
        return match ($this) {
            self::NORMAL => '普通参与',
            self::COUNTERSIGN => '会签参与',
        };
    }
}

