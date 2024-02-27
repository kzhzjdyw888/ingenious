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

namespace ingenious\service\interface;

use ingenious\core\Execution;
use ingenious\db\ProcessTask;
use ingenious\libs\utils\Dict;
use ingenious\model\CustomModel;
use ingenious\model\ProcessModel;
use ingenious\model\TaskModel;

interface ProcessTaskServiceInterface
{

    /**
     * 添加流程任务
     *
     * @param object $param
     *
     * @return bool
     */
    public function create(object $param): bool;

    /**
     * 更新流程任务
     *
     * @param $param
     *
     * @return bool
     */
    public function update($param): bool;

    /**
     * 通过id查询
     *
     * @param string $id
     *
     * @return \ingenious\db\ProcessTask|null
     */
    public function findById(string $id): ?ProcessTask;

    /**
     * 保存流程任务
     *
     * @param \ingenious\db\ProcessTask $processTask
     */
    public function saveProcessTask(ProcessTask $processTask): void;

    /**
     * 更新流程任务
     *
     * @param \ingenious\db\ProcessTask $processTask
     */
    public function updateProcessTask(ProcessTask $processTask): void;

    /**
     * 根据流程实例ID获取正在进行的任务
     *
     * @param string $processInstanceId 流程实例ID
     * @param string $taskNames         任务名称
     *
     * @return array|null
     */
    public function getDoingTaskList(string $processInstanceId, string $taskNames): ?array;

    /**
     * 根据流程实例ID获取已完成的任务
     *
     * @param string $processInstanceId 流程实例ID
     * @param string $taskNames         任务名称
     *
     * @return array|null
     */
    public function getDoneTaskList(string $processInstanceId, string $taskNames): ?array;

    /**
     * 将流程任务修改为已完成
     *
     * @param string                 $processTaskId 任务ID
     * @param string                 $operator      任务处理人
     * @param \ingenious\libs\utils\Dict $args
     */
    public function finishProcessTask(string $processTaskId, string $operator, Dict $args): void;

    /**
     * 废弃任务
     *
     * @param string                 $processTaskId
     * @param string                 $operator
     * @param \ingenious\libs\utils\Dict $args
     */
    public function abandonProcessTask(string $processTaskId, string $operator, Dict $args): void;

    /**
     * 根据任务模型和流程执行对象创建任务
     *
     * @param \ingenious\model\TaskModel $taskModel
     * @param \ingenious\core\Execution  $execution
     *
     * @return array|null
     */
    public function createTask(TaskModel $taskModel, Execution $execution): ?array;

    /**
     * 通过id获取流程任务
     *
     * @param string $id
     *
     * @return \ingenious\db\ProcessTask|null
     */
    public function getById(string $id): ?ProcessTask;

    /**
     * 向指定的任务id添加参与者
     *
     * @param string       $processTaskId
     * @param array|string $actors
     */
    public function addTaskActor(string $processTaskId, array|string $actors): void;

    /**
     * 向指定的任务id添加参与者
     *
     * @param string       $processTaskId
     * @param array|string $actors
     */
    public function addCandidateActor(string $processTaskId, array|string $actors): void;

    /**
     * 向指定任务移除参与者
     *
     * @param string       $processTaskId
     * @param array|string $actors
     */
    public function removeTaskActor(string $processTaskId, array|string $actors): void;

    /**
     * 根据taskId、operator，判断操作人operator是否允许执行任务
     *
     * @param \ingenious\db\ProcessTask $task     任务对象
     * @param string                $operator 操作人
     *
     * @return bool 是否允许操作
     */
    public function isAllowed(ProcessTask $task, string $operator): bool;

    /**
     * 根据任务ID获取参与者ID
     *
     * @param string $processTaskId
     *
     * @return array
     */
    public function getTaskActor(string $processTaskId): array;

    /**
     * 根据任务ID获取参与者ID
     *
     * @param \ingenious\model\TaskModel $model
     * @param \ingenious\core\Execution  $execution
     *
     * @return array
     */
    public function getTaskActors(TaskModel $model,Execution $execution): array;

    /**
     * 查询待办列表
     *
     * @param object $param
     *
     * @return array
     */
    public function todoList(object $param): array;

    /**
     * 查询已办列表
     *
     * @param object $param
     *
     * @return array
     */
    public function doneList(object $param): array;

    /**
     * 根据当前任务对象驳回至上一步处理
     *
     * @param \ingenious\model\ProcessModel $model       流程定义模型，用以获取上一步模型对象
     * @param \ingenious\db\ProcessTask     $currentTask 当前任务对象
     *
     * @return \ingenious\db\ProcessTask|null $Task 任务对象
     */
    public function rejectTask(ProcessModel $model, ProcessTask $currentTask): ?ProcessTask;

    /**
     * 获取可跳转的任务节点名称
     *
     * @param string $processInstanceId
     *
     * @return array
     */
    public function jumpAbleTaskNameList(string $processInstanceId): array;

    /**
     * 分页查询获取候选人
     *
     * @param object $query
     *
     * @return object
     */
    public function candidatePage(object $query): object;

    /**
     * 创建会签任务
     *
     * @param \ingenious\model\TaskModel $taskModel
     * @param \ingenious\core\Execution  $execution
     *
     * @return \ingenious\db\ProcessTask|array|null
     */
    public function createCountersignTask(TaskModel $taskModel, Execution $execution): ProcessTask|array|null;

    /**
     * 根据执行对象、自定义节点模型创建历史任务记录
     *
     * @param \ingenious\core\Execution    $execution
     * @param \ingenious\model\CustomModel $model
     *
     * @return \ingenious\db\ProcessTask|null
     */
    public function history(Execution $execution, CustomModel $model): ?ProcessTask;

}
