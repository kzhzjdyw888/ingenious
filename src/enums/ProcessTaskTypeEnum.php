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

class ProcessTaskTypeEnum implements \ingenious\interface\CodedEnum
{
    use EnumTrait;

    public const MAJOR = [0, "主办"];
    public const SECONDARY = [1, "协办"];
    public const RECORD = [2, "记录"];
}