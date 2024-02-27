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

namespace ingenious\model;

use ingenious\core\Execution;
use ingenious\interface\Action;
use ingenious\libs\traits\DynamicMethodTrait;
use ingenious\processor\handler\CreateTaskHandler;
use ingenious\processor\handler\StartSubProcessHandler;

/**
 * @method getTarget()
 * @method setName($getId)
 * @method setTo($getTargetNodeId)
 * @method setSource(\ingenious\model\NodeModel $nodeModel)
 * @method setExpr($getStr)
 * @method setTarget(mixed $nodeModel)
 * @method setEnabled(true $true)
 * @method getOutputs()
 * @method getTo()
 */
class TransitionModel extends BaseModel implements Action
{
    use DynamicMethodTrait;

    private NodeModel $source; // 边源节点引用
    private NodeModel $target; // 边目标节点引用
    private string $to; // 目标节点名称
    private string|null $expr; // 边表达式
    private string $g; // 边点坐标集合(x1,y1;x2,y2,x3,y3……)开始、拐角、结束
    private bool $enabled = false; // 是否可执行

    /**
     * @param \ingenious\core\Execution $execution
     */
    public function execute(Execution $execution): void
    {
        if (!$this->enabled) return;
        if ($this->target instanceof TaskModel) {
            // 创建阻塞任务
            $this->fire(new CreateTaskHandler($this->target), $execution);
        } else if ($this->target instanceof SubProcessModel) {
            // 如果为子流程，则启动子流程
            $this->fire(new StartSubProcessHandler($this->target), $execution);
        } else {
            $this->target->execute($execution);
        }
    }
}
