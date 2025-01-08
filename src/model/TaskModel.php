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

use madong\ingenious\interface\IExecution;
use madong\ingenious\interface\nodes\ITaskModel;
use madong\ingenious\libs\traits\DynamicPropsTrait;
use madong\ingenious\libs\utils\Logger;
use madong\ingenious\libs\utils\StringHelper;
use madong\ingenious\processor\handler\CountersignHandler;
use madong\ingenious\enums\CountersignTypeEnum;
use madong\ingenious\enums\ProcessTaskPerformTypeEnum;
use madong\ingenious\enums\ProcessTaskTypeEnum;

/**
 * @method getName()
 * @method getDisplayName()
 * @method getExpireTime()
 * @method getAssignee()
 * @method getAssigneeFormKey()
 * @method getAssignmentHandler()
 * @method getGroupKey()
 * @method getPerformType()
 * @method getCountersignType()
 * @method getTaskType()
 * @method getExt()
 * @method getCandidateHandler()
 * @method getReminderTime()
 * @method getReminderRepeat()
 * @method getAutoExecute()
 */
class TaskModel extends NodeModel implements ITaskModel
{

    use DynamicPropsTrait;

    private string|null $form; // 表单标识
    private string|null $assignee; // 参与人
    private string|null $assigneeFormKey; // 参与人表单key
    private string|null $groupKey; // 参与组用户标识
    private string|null $assignmentHandler; // 参与人处理类
    private string|int|ProcessTaskTypeEnum $taskType; // 任务类型(主办/协办)
    private string|int|ProcessTaskPerformTypeEnum $performType; // 参与类型(普通参与/会签参与)
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
    private string|int|CountersignTypeEnum $countersignType;
    // 会签完成条件
    /**
     * ● 全部完成：为空
     * ● 按数量通过：#nrOfCompletedInstances==n，这里表示n人完成任务，会签结束。
     * ● 按比例通过：#nrOfCompletedInstances/nrOfInstances==n，这里表示已完成会签数与总实例数达到一定比例时，会签结束
     * ● 一票通过：#nrOfCompletedInstances==1，这里表示1人完成任务，会签结束。
     * ● 一票否决：ONE_VOTE_VETO
     */
    private string|null $countersignCompletionCondition;

    public function exec(IExecution $execution): void
    {
        $sourceId = $execution->getProcessInstanceId() ?? '';
        Logger::debug('taskModel:' . ' 执行流程实例ID=' . $sourceId . '节点=' . $this->getName() ?? '');
        $performType = $this->getPerformType() ?? ProcessTaskPerformTypeEnum::NORMAL->value;
        if (StringHelper::equalsIgnoreCase(ProcessTaskPerformTypeEnum::COUNTERSIGN->value, $performType)) {
            // 会签任务处理
            $this->fire(new CountersignHandler($this), $execution);
            if ($execution->getMerged()) {
                $this->runOutTransition($execution);
            }
        } else {
            // 普通任务处理
            $this->runOutTransition($execution);
        }
    }
}
