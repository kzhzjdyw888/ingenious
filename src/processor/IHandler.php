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

