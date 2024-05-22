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

namespace ingenious\interface;

use ingenious\core\Execution;

/**
 * 任务拦截器对产生的任务进行拦截
 *
 * @author Mr.April
 * @since  1.0
 */
interface ProcessInterceptor
{

    /**
     * 拦截方法，参数为执行对象
     *
     * @param \ingenious\core\Execution $execution 执行对象。可从中获取执行的数据
     */
    public function intercept(Execution $execution): void;

}
