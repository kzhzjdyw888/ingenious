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

namespace madong\ingenious\interface;

use madong\ingenious\core\Execution;

/**
 * 任务拦截器对产生的任务进行拦截
 *
 * @author Mr.April
 * @since  1.0
 */
interface IProcessInterceptor
{

    /**
     * 拦截方法，参数为执行对象
     *
     * @param \madong\ingenious\core\Execution $execution 执行对象。可从中获取执行的数据
     */
    public function intercept(Execution $execution): void;

}
