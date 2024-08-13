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

namespace ingenious\enums\err;

use ingenious\libs\traits\EnumTrait;

/**
 *
 * 错误枚举
 * @author Mr.April
 * @since  1.0
 */
class LfErrEnum implements \ingenious\interface\CodedEnum
{
    use EnumTrait;

    public const NOT_FOUND_NEXT_NODE = [20010001, "decision节点无法确定下一步执行路线"];
    public const NOT_FOUND_PROCESS_DEFINE = [20010002, "没有流程定义"];
    public const NOT_FOUND_DOING_PROCESS_TASK = [20010003, "没有进行中的流程任务"];
    public const NOT_ALLOWED_EXECUTE = [20010004, "当前参与者不能执行该流程任务"];
    public const EXIST_UN_FINISH_INSTANCE = [20010005, "存在正在未完成的流程实例，不允许删除！"];
}
