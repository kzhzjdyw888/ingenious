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

namespace ingenious\parser\handler;

use ingenious\model\DecisionModel;
use ingenious\model\logicflow\LfNode;
use ingenious\model\NodeModel;
use ingenious\parser\AbstractNodeParser;
use ingenious\parser\NodeParser;

class DecisionParser extends AbstractNodeParser
{
    public function parseNode(LfNode $lfNode): void
    {
        $model      = $this->nodeModel; // 假设 nodeModel 是类的一个属性
        $properties = $lfNode->getProperties(); // 假设 getProperties 是 LfNode 类的一个方法
        $model->setExpr($properties->get(NodeParser::EXPR_KEY));
        $model->setHandleClass($properties->get(NodeParser::HANDLE_CLASS_KEY));
    }

    public function newModel(): NodeModel
    {
        return new DecisionModel();
    }

}
