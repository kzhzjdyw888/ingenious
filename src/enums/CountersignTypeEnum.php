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
 * 会签类型
 *
 * @author Mr.April
 * @since  1.0
 */
enum CountersignTypeEnum: int implements IEnum
{

    use EnumTrait;

    case PARALLEL = 0;
    case SEQUENTIAL = 1;

    public function label(): string
    {
        return match ($this) {
            self::PARALLEL => '并行会签',
            self::SEQUENTIAL => '串行会签',
        };
    }
}
