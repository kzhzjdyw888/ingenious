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

class ProcessTaskTypeEnum implements \ingenious\interface\CodedEnum
{
    use EnumTrait;

    public const MAJOR = [0, "主办"];
    public const SECONDARY = [1, "协办"];
    public const RECORD = [2, "记录"];
}
