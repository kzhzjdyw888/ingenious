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
