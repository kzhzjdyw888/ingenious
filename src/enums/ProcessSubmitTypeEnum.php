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
use ReflectionClass;

/**
 * 流程提交类型（操作类型）
 *
 * @author Mr.April
 * @since  1.0
 */
class ProcessSubmitTypeEnum implements \ingenious\interface\CodedEnum
{

    use EnumTrait;
    public const APPLY = [0, "发起申请"];
    public const AGREE = [1, "同意申请"];
    public const REJECT = [2, "拒绝申请"];
    public const ROLLBACK = [3, "退回上一步"];
    public const JUMP = [4, "跳转"];
    public const RE_APPLY = [5, "重新提交"];
    public const ROLLBACK_TO_OPERATOR = [6, "退回发起人"];
    public const COUNTERSIGN_DISAGREE = [20, "拒绝申请"];

}
