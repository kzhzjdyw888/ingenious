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
use ingenious\enums\ProcessEventTypeEnum;
use ingenious\event\ProcessEventService;
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
        // 1.把实例任务修改为完成
        $execution->getEngine()->processInstanceService()->finishProcessInstance($execution->getProcessInstanceId());
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
    }
}