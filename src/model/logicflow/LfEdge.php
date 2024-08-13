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

class LfEdge
{
    use DynamicMethodTrait;

    private string $id; // 边唯一id
    private string $type; // 边类型
    private string $sourceNodeId; // 源节点id
    private string $targetNodeId; // 目标节点id
    private mixed $properties; // 边属性
    private mixed $text; // 边文本
    private mixed $startPoint; // 边开始点坐标
    private mixed $endPoint; // 边结束点坐标
    private mixed $pointsList; // 边所有点集合

}
