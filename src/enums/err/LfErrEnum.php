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

namespace madong\ingenious\enums\err;

use madong\ingenious\interface\IEnum;
use madong\ingenious\libs\traits\EnumTrait;

/**
 * 错误枚举
 *
 * @author Mr.April
 * @since  1.0
 */
enum LfErrEnum: int implements IEnum
{

    use EnumTrait;

    case NOT_FOUND_NEXT_NODE = 0;
    case NOT_FOUND_PROCESS_DEFINE = 1;
    case NOT_FOUND_DOING_PROCESS_TASK = 20010003;
    case NOT_ALLOWED_EXECUTE = 20010004;
    case EXIST_UN_FINISH_INSTANCE = 20010005;

    public function label(): string
    {
        return match ($this) {
            self::NOT_FOUND_NEXT_NODE => 'decision节点无法确定下一步执行路线',
            self::NOT_FOUND_PROCESS_DEFINE => '没有流程定义',
            self::NOT_FOUND_DOING_PROCESS_TASK => '没有进行中的流程任务',
            self::NOT_ALLOWED_EXECUTE => '当前参与者不能执行该流程任务',
            self::EXIST_UN_FINISH_INSTANCE => '存在正在未完成的流程实例，不允许删除！',
        };
    }
}
