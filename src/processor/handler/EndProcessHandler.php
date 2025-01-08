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


use madong\ingenious\core\Execution;
use madong\ingenious\event\ProcessEventService;
use madong\ingenious\ex\LFlowException;
use madong\ingenious\interface\IExecution;
use madong\ingenious\libs\utils\StringHelper;
use madong\ingenious\model\EndModel;
use madong\ingenious\processor\IHandler;
use madong\ingenious\enums\ProcessConstEnum;
use madong\ingenious\enums\ProcessEventTypeEnum;
use madong\ingenious\enums\ProcessSubmitTypeEnum;

/**
 * 结束流程实例的处理器
 *
 * @author Mr.April
 * @since  1.0
 */
class EndProcessHandler implements IHandler
{

    private EndModel $endModel;

    public function __construct(EndModel $endModel)
    {
        $this->endModel = $endModel;
    }

    public function handle(IExecution $execution): void
    {

        try {
            $submitType = $execution->getArgs()->get(ProcessConstEnum::SUBMIT_TYPE->value, ProcessSubmitTypeEnum::AGREE->value);
            // 1.更改实例完成状态
            if (StringHelper::equalsIgnoreCase($submitType, ProcessSubmitTypeEnum::REJECT->value)) {
                $execution->getEngine()->processInstanceService()->rejectProcessInstance($execution->getProcessInstanceId());
            } else {
                $execution->getEngine()->processInstanceService()->finishProcessInstance($execution->getProcessInstanceId());
            }

            // 2.发布流程结束事件
            ProcessEventService::publishNotification(ProcessEventTypeEnum::PROCESS_INSTANCE_END->value, $execution->getProcessInstanceId());
            // 3.如果子流程存在父流程实例，则执行父流程的子流程节点模型方法
            $processInstance = $execution->getProcessInstance();
            if (!empty($processInstance->getData('parent_id'))) {
                $parentInstance = $execution->getEngine()->processInstanceService()->getById($processInstance->getData('parent_id'));
                if ($parentInstance == null) return;
                $pm = $execution->getEngine()->processDefineService()->getProcessModel($parentInstance->getData('process_define_id'));
                if ($pm == null) return;
                $spm          = $pm->getNode($processInstance->getData('parent_node_name'));
                $newExecution = new Execution();
                $newExecution->setEngine($execution->getEngine());
                $newExecution->setProcessModel($pm);
                $newExecution->setProcessInstance($parentInstance);
                $newExecution->setProcessInstanceId($parentInstance->getData('id'));
                $newExecution->setArgs($execution->getArgs());
                $spm->execute($newExecution);
                $execution->addTasks($newExecution->getProcessTaskList());
            }
        } catch (LFlowException $e) {
            throw new LFlowException($e->getMessage());
        }

    }
}
