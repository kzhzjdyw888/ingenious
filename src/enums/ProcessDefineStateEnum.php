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
 * 流程定义状态
 *
 * @author Mr.April
 * @since  1.0
 */
class ProcessDefineStateEnum implements \ingenious\interface\CodedEnum
{
    use EnumTrait;

    public const DISABLE = [0, "禁用"];
    public const ENABLE = [1, "启用"];

}
