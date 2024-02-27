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

namespace ingenious\parser\handler;

use ingenious\model\JoinModel;
use ingenious\model\logicflow\LfNode;
use ingenious\model\NodeModel;
use ingenious\parser\AbstractNodeParser;

class JoinParser extends AbstractNodeParser
{
    public function parseNode(LfNode $lfNode): void
    {

    }

    public function newModel(): NodeModel
    {
        return new JoinModel();
    }
}
