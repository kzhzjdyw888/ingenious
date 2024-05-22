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
