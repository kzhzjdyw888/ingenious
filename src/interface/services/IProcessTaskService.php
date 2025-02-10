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
use madong\ingenious\interface\model\IProcessTask;
use madong\ingenious\interface\model\IProcessTaskActor;
use madong\ingenious\model\CustomModel;
use madong\ingenious\model\ProcessModel;
use madong\ingenious\model\TaskModel;

interface IProcessTaskService
{
    public function created(object $param): ?IProcessTask;

    public function updated(object $param): bool;

    public function list(object $param): array;

    public function findById(string|int $id): ?IProcessTask;

    public function saveProcessTask(IProcessTask $processTask): void;

    public function updateProcessTask(IProcessTask $processTask): void;

    public function getDoingTaskList(string|int $processInstanceId, string|int|array $taskNames): ?array;

    public function finishProcessTask(string|int $processTaskId, string|int $operator, IDict $args): void;

    public function abandonProcessTask(string $processTaskId, string $operator, IDict $args): void;

    public function createTask(TaskModel $taskModel, IExecution $execution): ?array;

    public function getById(string|int $id): ?IProcessTask;

    public function addTaskActor(string|int $processTaskId, array|string|int $actors): void;

    public function transfer(object $param): ?IProcessTaskActor;

    public function addCandidateActor(string|int $processTaskId, array|string $actors): void;

    public function removeTaskActor(string|int $processTaskId, array|string $actors): void;

    public function isAllowed(IProcessTask $task, string|int $operator): bool;

    public function getTaskActor($processTaskId): array;

    public function getTaskActors(TaskModel $model, IExecution $execution): array;

    public function rejectTask(ProcessModel $model, IProcessTask $currentTask): ?IProcessTask;

    public function jumpAbleTaskNameList(string|int $processInstanceId): array;

    public function candidatePage(IDict $query): object|array;

    public function createCountersignTask(TaskModel $taskModel, IExecution $execution): IProcessTask|array|null;

    public function history(IExecution $execution, CustomModel $model): ?IProcessTask;
}

