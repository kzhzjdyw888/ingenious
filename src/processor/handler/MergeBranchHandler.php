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

namespace ingenious\processor\handler;

use ingenious\core\Execution;
use ingenious\core\ServiceContext;
use ingenious\libs\utils\StringBuilder;
use ingenious\model\ForkModel;
use ingenious\model\JoinModel;
use ingenious\model\NodeModel;
use ingenious\model\TaskModel;
use ingenious\processor\IHandler;
use ingenious\service\interface\ProcessTaskServiceInterface;

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
        self::findForkTaskNames($this->joinModel, $buffer);
        return $buffer->toArray();
    }

    /**
     * 对join节点的所有输入变迁进行递归，查找join至fork节点的所有中间task元素
     *
     * @param \ingenious\model\NodeModel $node
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
     * @param string|int                 $processInstanceId
     * @param \ingenious\model\NodeModel $nodeModel
     *
     * @return bool
     */
    public static function isMerged(string|int $processInstanceId, NodeModel $nodeModel): bool
    {
        // 合并节点
        $buffer = new StringBuilder();
        MergeBranchHandler::findForkTaskNames($nodeModel, $buffer);
        $taskNames          = $buffer->toArray();
        $processTaskService = ServiceContext::findFirst(ProcessTaskServiceInterface::class);
        $result             = $processTaskService->getDoingTaskList($processInstanceId, $taskNames);
        return empty($result);
    }
}
