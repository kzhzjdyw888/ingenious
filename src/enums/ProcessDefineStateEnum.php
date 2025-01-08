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
 * 流程定义状态
 *
 * @author Mr.April
 * @since  1.0
 */
enum ProcessDefineStateEnum: int implements IEnum
{

    use EnumTrait;

    case DISABLE = 0;
    case ENABLE = 1;

    public function label(): string
    {
        return match ($this) {
            self::DISABLE => '禁用',
            self::ENABLE => '启用',
        };
    }
}
