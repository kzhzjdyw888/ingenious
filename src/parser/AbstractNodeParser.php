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

use ingenious\model\logicflow\LfEdge;
use ingenious\model\logicflow\LfNode;
use ingenious\model\NodeModel;
use ingenious\model\TransitionModel;

abstract class AbstractNodeParser implements NodeParser
{
    // 节点模型对象
    protected NodeModel $nodeModel;

    public function parse(LfNode $lfNode, LfEdge|array $edges): void
    {
        $this->nodeModel = $this->newModel();

        // 解析基本信息
        $this->nodeModel->setName($lfNode->getId());

//        if ($lfNode->getText() !== null && !empty($lfNode->getText())) {
//            $this->nodeModel->setDisplayName($lfNode->getText()->{NodeParser::TEXT_VALUE_KEY} ?? '');
//        }

        $properties = $lfNode->getProperties();
        // 解析布局属性
        $x = $lfNode->getX();
        $y = $lfNode->getY();
        $w = !empty($properties->get(NodeParser::WIDTH_KEY)) ? $properties->get(NodeParser::WIDTH_KEY) : 0;
        $h = !empty($properties->get(NodeParser::HEIGHT_KEY)) ? $properties->get(NodeParser::HEIGHT_KEY) : 0;
        $this->nodeModel->setLayout(sprintf("%s,%s;%s,%s", $x, $y, $w, $h));
        // 解析拦截器
        $this->nodeModel->setPreInterceptors($properties->get(NodeParser::PRE_INTERCEPTORS_KEY));
        $this->nodeModel->setPostInterceptors($properties->get(NodeParser::POST_INTERCEPTORS_KEY));
        // 解析输出边
        $nodeEdges = $this->getEdgeBySourceNodeId($lfNode->getId(), $edges);
        foreach ($nodeEdges as $edge) {
            $transitionModel = new TransitionModel();
            $transitionModel->setName($edge->getId());
            $transitionModel->setTo($edge->getTargetNodeId());
            $transitionModel->setSource($this->nodeModel);
            if (!empty($edge->getProperties())) {
                //边表输出正则表达式
                $transitionModel->setExpr($edge->getProperties()->get(NodeParser::EXPR_KEY));
            }
            if (!empty($edge->getPointsList())) {
                // x1,y1;x2,y2;x3,y3……
                $pointsList = $edge->getPointsList();
                $gValues    = [];
                foreach ($pointsList as $point) {
                    $gValues[] = $point->x . "," . $point->y;
                }
                $transitionModel->setG(implode(";", $gValues));
            } else {
                if (!empty($edge->getStartPoint()) && !empty($edge->getEndPoint())) {
                    $startPointX = $edge->getStartPoint()->getX();
                    $startPointY = $edge->getStartPoint()->getY();
                    $endPointX   = $edge->getEndPoint()->getX();
                    $endPointY   = $edge->getEndPoint()->getY();
                    $transitionModel->setG(sprintf("%s,%s;%s,%s", $startPointX, $startPointY, $endPointX, $endPointY));
                }
            }
            $output   = $this->nodeModel->getOutputs();
            $output[] = $transitionModel;
            $this->nodeModel->setOutputs($output);
        }
        // 调用子类特定解析方法
        $this->parseNode($lfNode);
    }

    /**
     * 子类实现此类完成特定解析
     *
     * @param \ingenious\model\logicflow\LfNode $lfNode
     */
    abstract function parseNode(LfNode $lfNode): void;

    /**
     * 由子类各自创建节点模型对象
     *
     * @return \ingenious\model\NodeModel
     */
    abstract function newModel(): NodeModel;

    public function getModel(): NodeModel
    {
        return $this->nodeModel;
    }

    /**
     * 获取节点输入
     *
     * @param $targetNodeId
     * @param $edges
     *
     * @return array
     */
    private function getEdgeByTargetNodeId($targetNodeId, $edges): array
    {
        return array_filter($edges, function ($edge) use ($targetNodeId) {
            return $edge->getTargetNodeId() === $targetNodeId;
        });
    }

    /**
     * 获取节点输出
     *
     * @param $sourceNodeId
     * @param $edges
     *
     * @return array
     */
    private function getEdgeBySourceNodeId($sourceNodeId, $edges): array
    {
        return array_filter($edges, function ($edge) use ($sourceNodeId) {
            return $edge->getSourceNodeId() === $sourceNodeId;
        });
    }

}