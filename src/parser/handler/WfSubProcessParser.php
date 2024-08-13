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
