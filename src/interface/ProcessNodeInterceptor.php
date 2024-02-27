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
 * 流程节点-拦截器
 *
 * @author Mr.April
 * @since  1.0
 */
interface ProcessNodeInterceptor
{

    /**
     * 拦截方法，参数为执行对象
     *
     * @param \ingenious\core\Execution $execution
     */
    public function intercept(Execution $execution);
}
