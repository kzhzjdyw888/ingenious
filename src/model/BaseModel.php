<?php
/**
 *+------------------
 * Lflow
 *+------------------
 * Copyright (c) 2023~2030 gitee.com/liu_guan_qing All rights reserved.本版权不可删除，侵权必究
 *+------------------
 * Author: Mr.April(405784684@qq.com)
 *+------------------
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
