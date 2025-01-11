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


use madong\ingenious\interface\IExecution;
use madong\helper\Dict;
use madong\ingenious\libs\utils\Expression;
use madong\ingenious\libs\utils\StringHelper;
use madong\ingenious\model\TaskModel;
use madong\ingenious\parser\INodeParser;
use madong\ingenious\processor\IHandler;
use madong\ingenious\enums\CountersignTypeEnum;
use madong\ingenious\enums\ProcessConstEnum;

class CountersignHandler implements IHandler
{

    private TaskModel $taskModel;

    public function __construct(TaskModel $taskModel)
    {
        $this->taskModel = $taskModel;
    }

    public function handle(IExecution $execution): void
    {
        $isMerged        = false;
        $countersignType = $this->taskModel->getCountersignType();//兼容旧版本
        if (!empty($this->taskModel->getExt()->get(INodeParser::EXT_FIELD_COUNTERSIGN_TYPE_KEY))) {
            $countersignType = CountersignTypeEnum::codeOf($this->taskModel->getExt()->get(INodeParser::EXT_FIELD_COUNTERSIGN_TYPE_KEY), CountersignTypeEnum::PARALLEL);//会签类型
        }
        $countersignCompletionCondition = $this->taskModel->getExt()->get("countersign_completion_condition", "");//会签完成条件
        $isRejected                     = $this->taskModel->getExt()->get('countersignatureRejected', false);//会签拒绝是否驳回未实现功能
        $prefix                         = ProcessConstEnum::COUNTERSIGN_VARIABLE_PREFIX->value . $this->taskModel->getName() . "_";
        // 会签办理人列表
        $operatorList = $execution->getArgs()->get($prefix . ProcessConstEnum:: COUNTERSIGN_OPERATOR_LIST->value, []);
        // 循环计数器，办理人在列表中的索引
        $loopCounter = array_search($execution->getOperator(), $operatorList);
        // 追加计数器
        if ($loopCounter) {
            $execution->getArgs()->put($prefix . ProcessConstEnum:: LOOP_COUNTER->value, $loopCounter);
        }
        // 追加已完成数量
        $execution->getArgs()->put($prefix . ProcessConstEnum:: NR_OF_COMPLETED_INSTANCES->value, $execution->getArgs()->get($prefix . ProcessConstEnum:: NR_OF_COMPLETED_INSTANCES->value, 0) + 1);
        /**
         * ● 全部通过：为空
         * ● 按数量通过：#nrOfCompletedInstances==n，这里表示n人完成任务，会签结束。
         * ● 按比例通过：#nrOfCompletedInstances/nrOfInstances==n，这里表示已完成会签数与总实例数达到一定比例时，会签结束
         * ● 一票通过：#nrOfCompletedInstances==1，这里表示1人完成任务，会签结束。
         * ● 一票否决：ONE_VOTE_VETO
         */
        if (StringHelper::equalsIgnoreCase('ONE_VOTE_VETO', $countersignCompletionCondition)) {
            // 一票否决
            if ($execution->getArgs()->containsKey(ProcessConstEnum::COUNTERSIGN_DISAGREE_FLAG->value)) {
                // 存在拒绝标识，则直接通过
                $isMerged = true;
            }
        } else if (!empty($countersignCompletionCondition)) {
            // 根据条件判断是否通过
            $dict = new Dict();
            foreach ($execution->getArgs()->getAll() as $key => $value) {
                $newKey = str_replace($prefix, '', $key);
                $dict->put($newKey, $value);
            }
            //表达式条件
            $isMerged = Expression::eval($countersignCompletionCondition, $dict->getAll());
        }
        if (!$isMerged && StringHelper::equalsIgnoreCase(CountersignTypeEnum::SEQUENTIAL->value, $countersignType)) {
            // 串行未通过，则判断是否为最后一个
            if ($loopCounter == end($operatorList)) {
                $isMerged = true;
            } else {
                // 非最后一个，则继续创建会签任务
                $execution->getEngine()->processTaskService()->createCountersignTask($this->taskModel, $execution);
            }
        }

        if (!$isMerged && StringHelper::equalsIgnoreCase(CountersignTypeEnum::PARALLEL->value, $countersignType)) {
            // 是否所有会签任务已完成
            $doingTasks = $execution->getEngine()->processTaskService()->getDoingTaskList($execution->getProcessInstanceId(), '');
            $isMerged   = count($doingTasks) === 0; // 如果任务列表为空，$isMerged 为 true
            if (!$isMerged) {
                // 未通过，更新已完成实例数量
                $addVariable = new Dict ();
                $addVariable->put($prefix . ProcessConstEnum:: NR_OF_COMPLETED_INSTANCES->value, $execution->getArgs()->get($prefix . ProcessConstEnum:: NR_OF_COMPLETED_INSTANCES->value));
                $execution->getEngine()->processInstanceService()->addVariable($execution->getProcessInstanceId(), $addVariable);
            }
        }
        if ($isMerged) {
            //筛选会签变量key
            $keys = [];
            foreach ($execution->getArgs()->getAll() as $key => $value) {
                if (str_starts_with($key, $prefix)) {
                    // 如果是，则添加到 $keys 数组中
                    $keys[] = $key;
                }
            }
            // 如果可以合并，则把流程实例中的会签参数清空
            $execution->getEngine()->processInstanceService()->removeVariable($execution->getProcessInstanceId(), $keys);
            // 如果为并行会签，需将其他会签任务设置为废弃
            if (StringHelper::equalsIgnoreCase(CountersignTypeEnum::PARALLEL->value, $countersignType)) {
                $t = $execution->getEngine()->processTaskService()->getDoingTaskList($execution->getProcessInstanceId(), $this->taskModel->getName());
                foreach ($t as $value) {
                    $execution->getEngine()->processTaskService()->abandonProcessTask($t->getData('id'), ProcessConstEnum::AUTO_ID->value, $execution->getArgs());
                }
            }
        }
        $execution->setMerged($isMerged);
    }
}
