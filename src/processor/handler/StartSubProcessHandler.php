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
use ingenious\ex\LFlowException;
use ingenious\model\SubProcessModel;
use ingenious\processor\IHandler;

class StartSubProcessHandler implements IHandler
{

    private SubProcessModel $model;

    public function __construct(SubProcessModel $model)
    {
        $this->model = $model;
    }

    public function handle(Execution $execution): void
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
