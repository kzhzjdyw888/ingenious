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

namespace ingenious\model;

use ingenious\core\Execution;
use ingenious\libs\traits\DynamicMethodTrait;
use ingenious\libs\utils\ReflectUtil;

class DecisionModel extends NodeModel
{

    use DynamicMethodTrait;

    private string|null $expr; // 决策表达式
    private string|null $handleClass; // 决策处理类

    public function exec(Execution $execution)
    {

    }



}
