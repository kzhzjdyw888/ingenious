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
