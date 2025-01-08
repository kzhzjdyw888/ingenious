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

namespace madong\ingenious\model\logicflow;

use madong\ingenious\libs\traits\DynamicPropsTrait;

/**
 * @method getText()
 * @method getId()
 * @method getProperties()
 * @method getX()
 * @method getY()
 */
class LfPoint
{
    use DynamicPropsTrait;
    private int $x; // 节点中心点x轴坐标
    private int $y; // 节点中心点y轴坐标

}
