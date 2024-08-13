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
