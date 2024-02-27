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

namespace ingenious\parser;

use ingenious\core\ServiceContext;
use ingenious\libs\utils\ArrayHelper;
use ingenious\libs\utils\Dict;
use ingenious\libs\utils\ModelUtils;
use ingenious\model\logicflow\LfEdge;
use ingenious\model\logicflow\LfModel;
use ingenious\model\logicflow\LfNode;
use ingenious\model\NodeModel;
use ingenious\model\ProcessModel;
use ingenious\model\TaskModel;

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
     * @return \ingenious\model\ProcessModel
     * @throws \ReflectionException
     */
    public static function parse($inputStream): ProcessModel
    {
        $jsonObj      = ArrayHelper::arrayToObject($inputStream);
        $lfModel      = ModelUtils::jsonObjToLfModel($jsonObj, LfModel::class);
        $processModel = new ProcessModel();
        $nodes        = [];
        $edges        = [];
        foreach ($lfModel->getNodes() as $node) {
            $properties = new Dict();
            $properties->putAll($node->properties ?? (object)[]);
            $node->properties = $properties;
            $nodes[]          = ModelUtils::jsonObjToLfModel($node, LfNode::class);;
        }

        foreach ($lfModel->getEdges() as $edge) {
            $properties = new Dict();
            $properties->putAll($edge->properties ?? (object)[]);
            $edge->properties = $properties;
            $edges[]          = ModelUtils::jsonObjToLfModel($edge, LfEdge::class);
        }

        if (empty($nodes) || empty($edges)) {
            return $processModel;
        }
        // 流程定义基本信息
        $processModel->setName($lfModel->getName());
        $processModel->setDisplayName($lfModel->getDisplayName());
        $processModel->setType($lfModel->getType());
        $processModel->setInstanceUrl($lfModel->getInstanceUrl());
        $processModel->setInstanceNoClass($lfModel->getInstanceNoClass());
        $processModel->setPostInterceptors($lfModel->getPostInterceptors());
        $processModel->setPreInterceptors($lfModel->getPreInterceptors());
        // 流程节点信息
        foreach ($nodes as $node) {
            $type       = str_replace(NodeParser::NODE_NAME_PREFIX, "", $node->getType());
            $nodeParser = ServiceContext::findByName($type, NodeParser::class);
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
