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
use madong\ingenious\ex\LFlowException;
use madong\ingenious\interface\IExecution;
use madong\ingenious\model\SubProcessModel;
use madong\ingenious\processor\IHandler;

class StartSubProcessHandler implements IHandler
{

    private SubProcessModel $model;

    public function __construct(SubProcessModel $model)
    {
        $this->model = $model;
    }

    public function handle(IExecution $execution): void
    {
        $processDefine = $execution->getEngine()->processDefineService()->getProcessDefineByVersion($this->model->getName(), $this->model->getVersion());
        if ($processDefine == null) {
            throw new LFlowException("子流程" . $this->model->getName() . "定义不存在");
        }
        $parentId       = $execution->getProcessInstanceId();
        $parentNodeName = $this->model->getName();
        $execution->getEngine()->startProcessInstanceById($processDefine->getData('id'), $execution->getOperator(), $execution->getArgs(), $parentId, $parentNodeName);
    }
}
