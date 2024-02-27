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

/**
 * 会签类型
 *
 * @author Mr.April
 * @since  1.0
 */
class CountersignTypeEnum implements \ingenious\interface\CodedEnum
{
    use EnumTrait;

    public const PARALLEL = [0, "并行会签"];
    public const SEQUENTIAL = [1, "串行会签"];

}
