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

namespace madong\ingenious\libs\traits;

use madong\ingenious\core\ServiceContext;
use madong\ingenious\interface\services\IProcessCcInstanceService;
use madong\ingenious\interface\services\IProcessDefineService;
use madong\ingenious\interface\services\IProcessDesignService;
use madong\ingenious\interface\services\IProcessInstanceHistoryService;
use madong\ingenious\interface\services\IProcessInstanceService;
use madong\ingenious\interface\services\IProcessTaskHistoryService;
use madong\ingenious\interface\services\IProcessTaskService;
use madong\ingenious\interface\services\IProcessTypeService;

trait ProcessServiceTrait
{
    public function processDefineService(): ?IProcessDefineService
    {
        return ServiceContext::find(IProcessDefineService::class);
    }

    public function processInstanceService(): ?IProcessInstanceService
    {
        return ServiceContext::find(IProcessInstanceService::class);
    }

    public function processInstanceHistoryService(): ?IProcessInstanceHistoryService
    {
        return ServiceContext::find(IProcessInstanceHistoryService::class);
    }

    public function processTaskService(): ?IProcessTaskService
    {
        return ServiceContext::find(IProcessTaskService::class);
    }

    public function processTaskHistoryService(): ?IProcessTaskHistoryService
    {
        return ServiceContext::find(IProcessTaskHistoryService::class);
    }

    public function processTypeService(): ?IProcessTypeService
    {
        return ServiceContext::find(IProcessTypeService::class);
    }

    public function processCcInstanceService(): ?IProcessCcInstanceService
    {
        return ServiceContext::find(IProcessCcInstanceService::class);
    }

    public function processDesignService(): ?IProcessDesignService
    {
        return ServiceContext::find(IProcessDesignService::class);
    }
}
