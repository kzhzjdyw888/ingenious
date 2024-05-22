<?php
/**
 *+------------------
 * Ingenious
 *+------------------
 * Copyright (c) https://gitee.com/ingenstream/ingenious  All rights reserved. 本版权不可删除，侵权必究
 *+------------------
 * Author: Mr. April (405784684@qq.com)
 *+------------------
 * Software Registration Number: 2024SR0694589
 * Official Website: http://www.ingenstream.cn
 */

namespace ingenious\model;

use ingenious\core\Execution;
use ingenious\enums\ProcessTaskPerformTypeEnum;
use ingenious\libs\traits\DynamicMethodTrait;
use ingenious\libs\utils\Logger;
use ingenious\libs\utils\StringHelper;
use ingenious\processor\handler\CountersignHandler;

/**
 * @method getName()
 * @method getDisplayName()
 * @method getExpireTime()
 * @method getAssignee()
 * @method getAssignmentHandler()
 * @method getPerformType()
 * @method getCountersignType()
 * @method getTaskType()
 * @method getExt()
 */
class TaskModel extends NodeModel
{

    use DynamicMethodTrait;

    private string|null $form; // 表单标识
    private string|null $assignee; // 参与人
    private string|null $assignmentHandler; // 参与人处理类
    private array $taskType; // 任务类型(主办/协办)
    private array $performType; // 参与类型(普通参与/会签参与)
    private string|null $reminderTime; // 提醒时间
    private string|null $reminderRepeat; // 重复提醒间隔
    private string|null $expireTime; // 期待任务完成时间变量key
    private string|null $autoExecute; // 到期是否自动执行Y/N
    private string|null $callback; // 自动执行回调类
    private mixed $ext; // 自定义扩展属性
    // 候选用户标识
    private string|null $candidateUsers; // ext.getStr("candidateUsers");
    // 候选用户组标识
    private string|null $candidateGroups; // ext.getStr("candidateGroups");
    // 候选用户处理类字符串
    private string|null $candidateHandler; // ext.getStr("candidateHandler");
    // 会签类型 PARALLEL表示并行会签，SEQUENTIAL表示串行会签
    private array|null $countersignType;
    // 会签完成条件
    /**
     * ● 全部完成：为空
     * ● 按数量通过：#nrOfCompletedInstances==n，这里表示n人完成任务，会签结束。
     * ● 按比例通过：#nrOfCompletedInstances/nrOfInstances==n，这里表示已完成会签数与总实例数达到一定比例时，会签结束
     * ● 一票通过：#nrOfCompletedInstances==1，这里表示1人完成任务，会签结束。
     * ● 一票否决：ONE_VOTE_VETO
     */
    private string|null $countersignCompletionCondition;

    public function exec(Execution $execution): void
    {
        $sourceId = $execution->getProcessInstanceId() ?? '';
        Logger::debug('taskModel:' . ' 执行流程实例ID=' . $sourceId . '节点=' . $this->getName() ?? '');
        if ($this->performType == null || StringHelper::equalsIgnoreCase(ProcessTaskPerformTypeEnum::COUNTERSIGN[0], $this->getPerformType()[0] ?? 0)) {
            // 会签任务处理
            $this->fire(new CountersignHandler($this), $execution);
            if ($execution->getMerged()) {
                $this->runOutTransition($execution);
            }
        } else {
            $this->runOutTransition($execution);
        }
    }
}
