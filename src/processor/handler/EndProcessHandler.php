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

namespace ingenious\processor\handler;

use ingenious\core\Execution;
use ingenious\enums\ProcessConst;
use ingenious\enums\ProcessEventTypeEnum;
use ingenious\enums\ProcessSubmitTypeEnum;
use ingenious\event\ProcessEventService;
use ingenious\ex\LFlowException;
use ingenious\libs\utils\StringHelper;
use ingenious\model\EndModel;
use ingenious\processor\IHandler;

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

    public function handle(Execution $execution): void
    {
        try {
            $submitType = $execution->getArgs()->get(ProcessConst::SUBMIT_TYPE, ProcessSubmitTypeEnum::AGREE[0]);
            // 1.更改实例完成状态
            if (StringHelper::equalsIgnoreCase($submitType, ProcessSubmitTypeEnum::REJECT[0])) {
                $execution->getEngine()->processInstanceService()->rejectProcessInstance($execution->getProcessInstanceId());
            } else {
                $execution->getEngine()->processInstanceService()->finishProcessInstance($execution->getProcessInstanceId());
            }

            // 2.发布流程结束事件
            ProcessEventService::publishNotification(ProcessEventTypeEnum::PROCESS_INSTANCE_END[0], $execution->getProcessInstanceId());
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
