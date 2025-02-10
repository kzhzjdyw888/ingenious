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

namespace madong\ingenious\interface\services;

use madong\interface\IDict;
use madong\ingenious\interface\IExecution;
use madong\ingenious\interface\model\IProcessDefine;
use madong\ingenious\interface\model\IProcessInstance;
use madong\ingenious\model\TaskModel;

/**
 * 流程实例服务类
 *
 * @author Mr.April
 * @since  1.0
 */
interface IProcessInstanceService
{
    public function created(object $param): ?IProcessInstance;

    public function updated(object $param): bool;

    public function list(object $param): array;

    public function findById(string|int $id): ?IProcessInstance;

    public function finishProcessInstance(string|int $processInstanceId): void;

    public function rejectProcessInstance(string|int $processInstanceId): void;

    public function createProcessInstance(IProcessDefine $processDefine, string $operator, IDict $args, string|null $parentId = '', string|null $parentNodeName = ''): ?IProcessInstance;

    public function addVariable(string|int $processInstanceId, IDict $args): void;

    public function removeVariable(string|int $processInstanceId, string|array $keys): void;

    public function saveProcessInstance(IProcessInstance $processInstance): ?IProcessInstance;

    public function interrupt(string|int $processInstanceId, string|int $operator): void;

    public function resume(string|int $processInstanceId, string|int $operator): void;

    public function cascadeDelete(string|int|array $processInstanceId, string|null|int $operator = null): array;

    public function pending(string|int $processInstanceId, string|int $operator): void;

    public function activate(string|int $processInstanceId, string|int $operator): void;

    public function updateProcessInstance(IProcessInstance $processInstance): void;

    public function getById(string $id): ?IProcessInstance;

    public function startAndExecute(string|int $processDefineId, IDict $args): ?IProcessInstance;

    public function highLight(string|int $processInstanceId): array;

    public function calculateTimeDifference($startTimestamp, $endTimestamp): string;

    public function approvalRecord(string|int $processInstanceId): array;

    public function withdraw(string|int|array $processInstanceId, string|int $operator): void;

    public function updateCountersignVariable(TaskModel $taskModel, IExecution $execution, array $taskActors): void;

    public function createCCInstance(string|int $processInstanceId, string|int $creator, string|int $actorIds): void;

    public function updateCCStatus(string|int $processInstanceId, string|int $actorId): void;

    public function ccInstancePage(object $param): array;
}
