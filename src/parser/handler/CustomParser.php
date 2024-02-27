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

use ingenious\enums\ProcessConst;
use ingenious\model\CustomModel;
use ingenious\model\logicflow\LfNode;
use ingenious\model\NodeModel;
use ingenious\parser\AbstractNodeParser;
use ingenious\parser\NodeParser;

class CustomParser extends AbstractNodeParser
{

    public function parseNode(LfNode $lfNode): void
    {
        $model      = $this->nodeModel; // 假设 nodeModel 是类的一个属性
        $properties = $lfNode->getProperties(); // 假设 getProperties 是 LfNode 类的一个方法
        //自定义方法如果有参数进行设置
        $model->setClazz($properties->get(NodeParser::CLASS_KEY)); // 假设 getStr 是 Properties 类的一个方法
        $model->setMethodName($properties->get(NodeParser::METHOD_NAME_KEY));
        $model->setArgs($properties->get(NodeParser::ARGS_KEY));
        $model->setVar($properties->get(NodeParser::RETURN_VAL_KEY, ProcessConst::CUSTOM_RETURN_VAL));

    }

    public function newModel(): NodeModel
    {
        return new CustomModel();

    }
}
