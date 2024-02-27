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

namespace ingenious\processor\handler;

use ingenious\core\Execution;
use ingenious\libs\utils\StringBuilder;
use ingenious\model\ForkModel;
use ingenious\model\JoinModel;
use ingenious\model\NodeModel;
use ingenious\model\TaskModel;
use ingenious\processor\IHandler;

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

    public function handle(Execution $execution): void
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
        $this->findForkTaskNames($this->joinModel, $buffer);
        return $buffer->toArray();
    }

    /**
     * 对join节点的所有输入变迁进行递归，查找join至fork节点的所有中间task元素
     *
     * @param \ingenious\model\NodeModel $node
     * @param                        $buffer
     */
    private function findForkTaskNames(NodeModel $node, &$buffer)
    {
        if ($node instanceof ForkModel) {
            return; // 跳过ForkModel类型的节点
        }

        $inputs = $node->getInputs();
        foreach ($inputs as $tm) {
            if ($tm->getSource() instanceof TaskModel) {
                $buffer->append($tm->getSource()->getName())->append(",");
            }
            $this->findForkTaskNames($tm->getSource(), $buffer); // 递归调用
        }
    }
}
