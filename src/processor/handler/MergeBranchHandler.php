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

namespace madong\ingenious\processor\handler;


use madong\ingenious\core\ServiceContext;
use madong\ingenious\interface\IExecution;
use madong\ingenious\interface\services\IProcessTaskService;
use madong\ingenious\libs\utils\StringBuilder;
use madong\ingenious\model\ForkModel;
use madong\ingenious\model\JoinModel;
use madong\ingenious\model\NodeModel;
use madong\ingenious\model\TaskModel;
use madong\ingenious\processor\IHandler;

/**
 * 合并分支操作处理器
 *
 * @author Mr.April
 * @since  1.0
 */
class MergeBranchHandler implements IHandler
{
    private JoinModel $joinModel;

    public function __construct(JoinModel $joinModel)
    {
        $this->joinModel = $joinModel;
    }

    public function handle(IExecution $execution): void
    {
        $engine = $execution->getEngine();

        $processTaskService = $engine->processTaskService();

        // 判断是否存在正在执行的任务，存在则不允许合并
        $execution->setMerged(empty($processTaskService->getDoingTaskList($execution->getProcessInstanceId(), $this->findActiveNodes())));
    }

    /**
     * 对join节点的所有输入变迁进行递归，查找join至fork节点的所有中间task元素
     *
     * @return array
     */
    public function findActiveNodes(): array
    {
        $buffer = new StringBuilder();
        self::findForkTaskNames($this->joinModel, $buffer);
        return $buffer->toArray();
    }

    /**
     * 对join节点的所有输入变迁进行递归，查找join至fork节点的所有中间task元素
     *
     * @param \madong\ingenious\model\NodeModel $node
     * @param                            $buffer
     */
    public static function findForkTaskNames(NodeModel $node, &$buffer)
    {
        if ($node instanceof ForkModel) {
            return; // 跳过ForkModel类型的节点
        }

        $inputs = $node->getInputs();
        foreach ($inputs as $tm) {
            if ($tm->getSource() instanceof TaskModel) {
                $buffer->append($tm->getSource()->getName())->append(",");
            }
            self::findForkTaskNames($tm->getSource(), $buffer); // 递归调用
        }
    }

    /**
     * 判断流程是否可合并
     *
     * @param string|int                               $processInstanceId
     * @param \madong\ingenious\model\NodeModel $nodeModel
     *
     * @return bool
     */
    public static function isMerged(string|int $processInstanceId, NodeModel $nodeModel): bool
    {
        // 合并节点
        $buffer = new StringBuilder();
        MergeBranchHandler::findForkTaskNames($nodeModel, $buffer);
        $taskNames          = $buffer->toArray();
        $processTaskService = ServiceContext::findFirst(IProcessTaskService::class);
        $result             = $processTaskService->getDoingTaskList($processInstanceId, $taskNames);
        return empty($result);
    }
}
