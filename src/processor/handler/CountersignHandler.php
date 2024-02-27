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
use ingenious\enums\CountersignTypeEnum;
use ingenious\enums\ProcessConst;
use ingenious\libs\utils\Dict;
use ingenious\libs\utils\ExpressionUtil;
use ingenious\libs\utils\StringHelper;
use ingenious\model\TaskModel;
use ingenious\processor\IHandler;

class CountersignHandler implements IHandler
{

    private TaskModel $taskModel;

    public function __construct(TaskModel $taskModel)
    {
        $this->taskModel = $taskModel;
    }

    public function handle(Execution $execution): void
    {
        $isMerged                       = false;
        $countersignType                = $this->taskModel->getExt()->get("countersignType", "PARALLEL");//会签类型
        $countersignCompletionCondition = $this->taskModel->getExt()->get("countersignCompletionCondition", "");//会签完成条件
        $isRejected                     = $this->taskModel->getExt()->get('countersignatureRejected', false);//会签拒绝是否驳回未实现功能
        $prefix                         = ProcessConst:: COUNTERSIGN_VARIABLE_PREFIX . $this->taskModel->getName() + "_";
        // 会签办理人列表
        $operatorList = $execution->getArgs()->get($prefix . ProcessConst:: COUNTERSIGN_OPERATOR_LIST);
        // 循环计数器，办理人在列表中的索引
        $loopCounter = array_search($execution->getOperator(), $operatorList);
        // 追加计数器
        $execution->getArgs()->put($prefix . ProcessConst:: LOOP_COUNTER, $loopCounter);
        // 追加已完成数量
        $execution->getArgs()->put($prefix . ProcessConst:: NR_OF_COMPLETED_INSTANCES, $execution->getArgs()->get($prefix . ProcessConst:: NR_OF_COMPLETED_INSTANCES, 0) + 1);
        /**
         * ● 全部通过：为空
         * ● 按数量通过：#nrOfCompletedInstances==n，这里表示n人完成任务，会签结束。
         * ● 按比例通过：#nrOfCompletedInstances/nrOfInstances==n，这里表示已完成会签数与总实例数达到一定比例时，会签结束
         * ● 一票通过：#nrOfCompletedInstances==1，这里表示1人完成任务，会签结束。
         * ● 一票否决：ONE_VOTE_VETO
         */
        if (StringHelper::equalsIgnoreCase('ONE_VOTE_VETO', $countersignCompletionCondition)) {
            // 一票否决
            if ($execution->getArgs()->containsKey(ProcessConst::COUNTERSIGN_DISAGREE_FLAG)) {
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
            $isMerged = ExpressionUtil:: eval($countersignCompletionCondition, $dict->getAll());
        }
        if (!$isMerged && StringHelper::equalsIgnoreCase(CountersignTypeEnum::SEQUENTIAL[0], $countersignType)) {
            // 串行未通过，则判断是否为最后一个
            if ($loopCounter == end($operatorList)) {
                $isMerged = true;
            } else {
                // 非最后一个，则继续创建会签任务
                $execution->getEngine()->processTaskService()->createCountersignTask($this->taskModel, $execution);
            }
        }

        if (!$isMerged && StringHelper::equalsIgnoreCase(CountersignTypeEnum::PARALLEL[0], $countersignType)) {

            // 是否所有会签任务已完成
            $isMerged = $execution->getEngine()->processTaskService()->getDoingTaskList($execution->getProcessInstanceId(), '');
            if (!$isMerged) {
                // 未通过，更新已完成实例数量
                $addVariable = new Dict ();
                $addVariable->put($prefix . ProcessConst:: NR_OF_COMPLETED_INSTANCES, $execution->getArgs()->get($prefix . ProcessConst:: NR_OF_COMPLETED_INSTANCES));
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
            if (StringHelper::equalsIgnoreCase(CountersignTypeEnum::PARALLEL[0], $countersignType)) {
                $t = $execution->getEngine()->processTaskService()->getDoingTaskList($execution->getProcessInstanceId(), $this->taskModel->getName());
                foreach ($t as $value) {
                    $execution->getEngine()->processTaskService()->abandonProcessTask($t->getData('id'), ProcessConst::AUTO_ID, $execution->getArgs());
                }
            }
        }
        $execution->setMerged($isMerged);
    }
}
