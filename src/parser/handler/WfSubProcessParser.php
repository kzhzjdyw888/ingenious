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
use ingenious\model\SubProcessModel;
use ingenious\parser\AbstractNodeParser;
use ingenious\parser\NodeParser;

class WfSubProcessParser extends AbstractNodeParser
{
    public function parseNode(LfNode $lfNode): void
    {
        $subProcessModel = $this->nodeModel;
        $properties      = $lfNode->getProperties();
        $subProcessModel->setForm($properties->get(NodeParser::FORM_KEY));
        $subProcessModel->setVersion($properties->get(NodeParser::VERSION_KEY));
    }

    public function newModel(): NodeModel
    {
        return new SubProcessModel();
    }

}
