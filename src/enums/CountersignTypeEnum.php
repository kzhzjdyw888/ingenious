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
