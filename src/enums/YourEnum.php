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

class YourEnum implements \ingenious\interface\CodedEnum
{
    use EnumTrait;

    public const NO = [0, "未读"];
    public const YES = [1, "已读"];

}
