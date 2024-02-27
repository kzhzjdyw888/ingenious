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

class IsDeleteEnum implements \ingenious\interface\CodedEnum
{
    use EnumTrait;

    public const NO = [0, "正常"];
    public const YES = [1, "删除"];

}
