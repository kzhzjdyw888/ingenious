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
declare (strict_types=1);

namespace ingenious\domain;

use DateTime;
use ingenious\interface\INoGenerator;
use ingenious\model\ProcessModel;

/**
 * 默认的流程实例编号生成器
 * 编号生成规则为:YmdHis-rand
 *
 * @author Mr.April
 * @since  1.0
 */
class DefaultNoGenerator implements INoGenerator
{

    public function generate(?ProcessModel $model): string
    {
        $dateTime = (new DateTime())->format('YmdHis');
        return $dateTime . rand(1000, 9999);
    }
}
