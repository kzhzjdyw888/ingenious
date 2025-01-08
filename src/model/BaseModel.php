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

namespace madong\ingenious\model;

use madong\ingenious\interface\IExecution;
use madong\ingenious\libs\traits\DynamicPropsTrait;
use madong\ingenious\processor\IHandler;

class BaseModel
{

    use DynamicPropsTrait;

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
     * @param \madong\ingenious\processor\IHandler   $handler
     * @param \madong\ingenious\interface\IExecution $execution
     */
    protected function fire(IHandler $handler, IExecution $execution): void
    {
        $handler->handle($execution);
    }

}
