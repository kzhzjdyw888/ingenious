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
declare (strict_types=1);

namespace ingenious\processor;

use ingenious\core\Execution;

/**
 * 流程各模型操控处理接口
 *
 * @author Mr.April
 * @since  1.0
 */
interface IHandler
{
    /**
     * 子类需要实现的方法，来处理具体的操作
     *
     * @param \ingenious\core\Execution $execution
     */
    public function handle(Execution $execution): void;
}

