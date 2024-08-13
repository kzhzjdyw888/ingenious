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

namespace ingenious\model;

use ingenious\core\Execution;
use ingenious\libs\traits\DynamicMethodTrait;
use ingenious\processor\IHandler;

class BaseModel
{

    use DynamicMethodTrait;

    /**
     * 元素名称
     */
    protected string $name = '';

    /**
     * 显示名称
     */
    protected string $display_name = '';

    /**
     * 将执行对象execution交给具体的处理器处理
     *
     * @param \ingenious\processor\IHandler $handler
     * @param \ingenious\core\Execution     $execution
     */
    protected function fire(IHandler $handler, Execution $execution): void
    {
        $handler->handle($execution);
    }

}
