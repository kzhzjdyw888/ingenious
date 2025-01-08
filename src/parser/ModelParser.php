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

namespace madong\ingenious\parser;

use madong\ingenious\core\ServiceContext;
use madong\ingenious\libs\utils\ArrayHelper;
use madong\ingenious\libs\utils\Dict;
use madong\ingenious\libs\utils\PropertyCopier;
use madong\ingenious\model\logicflow\LfEdge;
use madong\ingenious\model\logicflow\LfModel;
use madong\ingenious\model\logicflow\LfNode;
use madong\ingenious\model\ProcessModel;
use madong\ingenious\model\TaskModel;

class ModelParser
{

    /**
     * 确保对象的实例化是在受控的环境中进行
     */
    private function __construct()
    {
    }

    /**
     * parse
     *
     * @param $inputStream
     *
     * @return \madong\ingenious\model\ProcessModel
     * @throws \ReflectionException
     */
    public static function parse($inputStream): ProcessModel
    {
        $inputStream  = ArrayHelper::jsonToArray($inputStream);
        $jsonObj      = ArrayHelper::arrayToObject($inputStream);
        $lfModel      = PropertyCopier::jsonObjToLfModel($jsonObj, LfModel::class);
        $processModel = new ProcessModel();
        $nodes        = [];
        $edges        = [];
        foreach ($lfModel->getNodes() as $node) {
            $properties = new Dict();
            $properties->putAll($node->properties ?? (object)[]);
            $node->properties = $properties;
            $nodes[]          = PropertyCopier::jsonObjToLfModel($node, LfNode::class);;
        }

        foreach ($lfModel->getEdges() as $edge) {
            $properties = new Dict();
            $properties->putAll($edge->properties ?? (object)[]);
            $edge->properties = $properties;
            $edges[]          = PropertyCopier::jsonObjToLfModel($edge, LfEdge::class);
        }

        if (empty($nodes) || empty($edges)) {
            return $processModel;
        }
        // 流程定义基本信息
        $processModel->setName($lfModel->getName());
        $processModel->setDisplayName($lfModel->getDisplayName());
        $processModel->setType($lfModel->getType());
        $processModel->setInstanceUrl($lfModel->getInstanceUrl());
        $processModel->setExpireTime($lfModel->getExpireTime());
        $processModel->setInstanceNoClass($lfModel->getInstanceNoClass());
        $processModel->setPostInterceptors($lfModel->getPostInterceptors());
        $processModel->setPreInterceptors($lfModel->getPreInterceptors());
        // 流程节点信息
        foreach ($nodes as $node) {
            $type       = str_replace(INodeParser::NODE_NAME_PREFIX, "", $node->getType());
            $nodeParser = ServiceContext::find($type);
            if ($nodeParser != null) {
                $nodeParser->parse($node, $edges);
                $nodeModel  = $nodeParser->getModel() ?? [];
                $nodeAppend = $processModel->getNodes();
                //追加新的任务nodes
                $nodeAppend[] = $nodeParser->getModel();
                $processModel->setNodes($nodeAppend);
                //如果是Task任务节点追加到tasks list
                if ($nodeModel instanceof TaskModel) {
                    $tasksAppend   = $processModel->getTasks() ?? [];
                    $tasksAppend[] = $nodeModel;
                    $processModel->setTasks($tasksAppend);
                }
            }
        }
        // 循环节点模型，构造输入边、输出边的source、target
        foreach ($processModel->getNodes() as $node) {
            foreach ($node->getOutputs() as $transition) {
                $to = $transition->getTo();
                foreach ($processModel->getNodes() as $node2) {
                    if (strcasecmp($to, $node2->getName()) === 0) {
                        $inputsAppend   = $node2->getInputs() ?? [];
                        $inputsAppend[] = $transition;
                        $node2->setInputs($inputsAppend);
                        $transition->setTarget($node2);
                    }
                }
            }
        }
        return $processModel;
    }
}
