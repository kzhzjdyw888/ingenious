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

namespace ingenious\model\logicflow;

use ingenious\libs\traits\DynamicMethodTrait;

/**
 * @method getText()
 * @method getId()
 * @method getProperties()
 * @method getX()
 * @method getY()
 */
class LfPoint
{
    use DynamicMethodTrait;
    private int $x; // 节点中心点x轴坐标
    private int $y; // 节点中心点y轴坐标

}
