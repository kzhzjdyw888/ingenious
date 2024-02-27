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

namespace ingenious\enums;

use ingenious\libs\traits\EnumTrait;
use ingenious\model\BaseModel;
use ReflectionClass;

class ProcessTaskPerformTypeEnum implements \ingenious\interface\CodedEnum
{
    use EnumTrait;
    /**
     * 普通参与：一个或多个人同时参与一个任务，所有人只要其中一人执行完成，就能驱动任务节点往下一步执行
     */
    public const NORMAL = [0, "普通参与"];

    /**
     * 会签参与：给每个人都创建任务，满足一定条件时，才能驱动任务节点往下一步执行
     * 1. 所有人都执行完成
     * 2. 有一定比率执行完成时
     * 3. 某特殊人员执行完成时
     * ……等等
     */
    public const COUNTERSIGN = [1, "会签参与"];

}
