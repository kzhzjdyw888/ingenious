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

/**
 * 分支定义-元素
 *
 * @author Mr.April
 * @since  1.0
 */
class ForkModel extends NodeModel
{

    use DynamicMethodTrait;

    public function exec(Execution $execution): void
    {
        $this->runOutTransition($execution);
    }
}
