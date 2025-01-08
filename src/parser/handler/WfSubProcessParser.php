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

namespace madong\ingenious\parser\handler;

use madong\ingenious\model\logicflow\LfNode;
use madong\ingenious\model\NodeModel;
use madong\ingenious\model\SubProcessModel;
use madong\ingenious\parser\AbstractINodeParser;
use madong\ingenious\parser\INodeParser;

class WfSubProcessParser extends AbstractINodeParser
{
    public function parseNode(LfNode $lfNode): void
    {
        $subProcessModel = $this->nodeModel;
        $properties      = $lfNode->getProperties();
        $subProcessModel->setForm($properties->get(INodeParser::FORM_KEY));
        $subProcessModel->setVersion($properties->get(INodeParser::VERSION_KEY));
    }

    public function newModel(): NodeModel
    {
        return new SubProcessModel();
    }

}
