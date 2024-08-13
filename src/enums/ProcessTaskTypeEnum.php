<?php
/**
 * Copyright (C) 2024 Ingenstream
 * This software is licensed under the Apache-2.0 license.
 * A copy of the license can be found at http://www.apache.org/licenses/LICENSE-2.0
 * Official Website: http://www.ingenstream.cn
 * Author: Mr. April <405784684@qq.com>
 * Project: Ingenious
 * Repository: https://gitee.com/ingenstream/ingenious
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
