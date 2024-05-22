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
