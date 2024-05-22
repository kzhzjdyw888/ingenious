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
