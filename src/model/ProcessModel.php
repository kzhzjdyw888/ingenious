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

namespace madong\ingenious\model;


use madong\ingenious\core\ServiceContext;
use madong\ingenious\ex\LFlowException;
use madong\ingenious\interface\ICandidateHandler;
use madong\ingenious\interface\nodes\IProcessModel;
use madong\ingenious\libs\traits\DynamicPropsTrait;
use madong\ingenious\libs\utils\InstanceFactory;
use madong\ingenious\libs\utils\StringHelper;


/**
 * @method setName($getName)
 * @method setDisplayName($getDisplayName)
 * @method setType($getType)
 * @method setInstanceUrl($getInstanceUrl)
 * @method setInstanceNoClass($getInstanceNoClass)
 * @method setPostInterceptors($getPostInterceptors)
 * @method setPreInterceptors($getPreInterceptors)
 * @method getNodes()
 * @method setNodes(int $array_push)
 * @method getTasks()
 * @method setTasks()
 * @method getType()
 * @method getName()
 * @method getDisplayName()
 * @method getExpireTime()
 * @method setExpireTime($getExpireTime)
 */
class ProcessModel extends BaseModel implements IProcessModel
{
    use DynamicPropsTrait;

    private string $type; // 流程定义分类
    private string $instance_url; // 启动实例要填写的表单key
    private string $expire_time = ''; // 期待完成时间变量key
    private string $instance_no_class; // 实例编号生成器实现类
    private string $pre_interceptors; // 节点前置拦截器
    private string $post_interceptors; // 节点后置拦截器
    // 流程定义的所有节点
    private array $nodes = [];
    // 流程定义的所有任务节点
    private array $tasks = [];

    /**
     * 获取开始节点
     *
     * @return \madong\ingenious\model\StartModel|null
     */
    public function getStart(): ?StartModel
    {
        $startModel = null;
        foreach ($this->nodes as $node) {
            if ($node instanceof StartModel) {
                $startModel = $node;
                break;
            }
        }
        return $startModel;
    }

    /**
     * 获取process定义的指定节点名称的节点模型
     *
     * @param string $nodeName
     *
     * @return \madong\ingenious\model\NodeModel|null
     */
    public function getNode(string $nodeName): ?NodeModel
    {
        foreach ($this->nodes as $node) {
            if (StringHelper::equalsIgnoreCase($node->getName(), $nodeName)) {
                return $node;
            }
        }
        return null;
    }

    /**
     * 获取下一个任务节点模型集合
     *
     * @param string $nodeName
     *
     * @return array
     */
    public function getNextTaskModels(string $nodeName): array
    {
        $res       = [];
        $nodeModel = $this->getNode($nodeName);
        if (empty($nodeModel)) return $res;
        // 获取所有输出边的目标节点
        $nextNodeModelList = array_map(function ($item) {
            return $item->getTarget();
        }, $nodeModel->getOutputs());
        foreach ($nextNodeModelList as $item) {
            if ($item instanceof TaskModel) {
                $res[] = $item;
            }
        }

        if (empty($res)) {
            // 如果下一个节点不存在任务节点，递归往下找
            foreach ($nextNodeModelList as $item) {
                $taskModelList = $this->getNextTaskModels($item->getName());
                $res           = array_merge($res, $taskModelList);
            }
        }
        return $res;
    }

    /**
     *  获取下一个任务节点的候选人
     *
     * @param string $nodeName
     *
     * @return array
     */
    public function getNextTaskModelCandidates(string $nodeName): array
    {
        $res            = [];
        $nextTaskModels = $this->getNextTaskModels($nodeName);
        foreach ($nextTaskModels as $item) {
            $res = array_merge($res, $this->getCandidates($item));
        }
        return $res;
    }

    /**
     * 根据任务模型获取候选人
     *
     * @param \madong\ingenious\model\TaskModel $taskModel
     *
     * @return array
     */
    public function getCandidates(TaskModel $taskModel): array
    {
        $res = [];
        // 从上下文中查找候选人处理人
        $handlerList = ServiceContext::findAll(ICandidateHandler::class);
        foreach ($handlerList as $handler) {
            // 通过候选从处理类获取候选人集合
            $candidateList = $handler->handle($taskModel);
            if (!empty($candidateList)) {
                $res = array_merge($res, $candidateList);
            }
        }

        // 通过候选人处理类获取筛选人
        $candidateHandler = $taskModel->getCandidateHandler();
        if (!empty($candidateHandler) && class_exists($candidateHandler)) {
            $instanceFactory       = new InstanceFactory();
            $candidateHandlerClass = $instanceFactory->newInstance($candidateHandler);
            if (method_exists($candidateHandlerClass, 'handle')) {
                $candidateList = $candidateHandlerClass->handle($taskModel);
                if (!empty($candidateList)) {
                    $res = array_merge($res, $candidateList);
                }
            }
        }
        $res = array_unique($res, SORT_REGULAR);
        return array_values($res);
    }

    /**
     * 根据指定的节点类型返回流程定义中所有模型对象
     *
     * @param object|string $clazz
     *
     * @return array
     */
    public function getModels(object|string $clazz): array
    {
        // 检查 clazz 参数的类型
        if (is_string($clazz)) {
            // clazz 是字符串，使用反射来创建类的实例
            $instanceFactory = new InstanceFactory();
            $clazz           = $instanceFactory->newInstance($clazz);
        }

        if (!$clazz instanceof NodeModel) {
            throw new LFlowException('The provided clazz must be a string representing a class name or an instance of NodeMode.');
        }
        $models = [];
        $this->buildModels($models, $this->getStart()->getNextModels($clazz), $clazz);
        return $models;
    }

    private function buildModels(array &$models, array $nextModels, object $clazz): void
    {
        foreach ($nextModels as $nextModel) {
            $models[] = $nextModel;
        }
    }
}
