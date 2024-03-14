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

namespace ingenious\interface;

use ingenious\core\Execution;

/**
 * 决策处理器接口
 *
 * @author Mr.April
 * @since  1.0
 */
interface DecisionHandlerInterface
{
    /**
     * 定义决策方法，实现类需要根据执行对象做处理，并返回后置流转的name
     *
     * @param \ingenious\core\Execution $execution
     *
     * @return string
     */
    public function decide(Execution $execution): string;
}
