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
