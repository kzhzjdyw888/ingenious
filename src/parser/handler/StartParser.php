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

use ingenious\model\logicflow\LfNode;
use ingenious\model\NodeModel;
use ingenious\model\StartModel;
use ingenious\parser\AbstractNodeParser;

class StartParser extends AbstractNodeParser
{
    public function parseNode(LfNode $lfNode): void
    {

    }

    public function newModel():NodeModel
    {
        return new StartModel();
    }
}
