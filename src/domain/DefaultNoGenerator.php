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
namespace madong\ingenious\domain;

use DateTime;
use madong\ingenious\interface\INoGenerator;
use madong\ingenious\model\ProcessModel;

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
