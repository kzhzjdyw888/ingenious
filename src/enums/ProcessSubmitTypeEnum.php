<?php
/**
 *+------------------
 * ingenious
 *+------------------
 * Copyright (c) https://gitcode.com/motion-code  All rights reserved.
 *+------------------
 * Author: Mr. April (405784684@qq.com)
 *+------------------
 * Software Registration Number: 2024SR0694589
 * Official Website: https://madong.tech
 */

namespace madong\ingenious\enums;


use madong\ingenious\interface\IEnum;
use madong\ingenious\libs\traits\EnumTrait;

/**
 * 流程提交类型（操作类型）
 *
 * @author Mr.April
 * @since  1.0
 */
enum ProcessSubmitTypeEnum: int implements IEnum
{
    use EnumTrait;
    case APPLY = 0;                     // 发起申请
    case AGREE = 1;                     // 同意申请
    case REJECT = 2;                    // 拒绝申请
    case ROLLBACK = 3;                  // 退回上一步
    case JUMP = 4;                      // 跳转
    case RE_APPLY = 5;                  // 重新提交
    case ROLLBACK_TO_OPERATOR = 6;      // 退回发起人
    case COUNTERSIGN_DISAGREE = 20;     // 拒绝申请

    public function label(): string
    {
        return match ($this) {
            self::APPLY => '发起申请',
            self::AGREE => '同意申请',
            self::REJECT => '拒绝申请',
            self::ROLLBACK => '退回上一步',
            self::JUMP => '跳转',
            self::RE_APPLY => '重新提交',
            self::ROLLBACK_TO_OPERATOR => '退回发起人',
            self::COUNTERSIGN_DISAGREE => '拒绝申请',
        };
    }
}
