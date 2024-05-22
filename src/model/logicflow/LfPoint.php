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
