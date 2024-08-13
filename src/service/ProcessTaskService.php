<?php
/**
 * Copyright (C) 2024 Ingenstream
 * This software is licensed under the Apache-2.0 license.
 * A copy of the license can be found at http://www.apache.org/licenses/LICENSE-2.0
 * Official Website: http://www.ingenstream.cn
 * Author: Mr. April <405784684@qq.com>
 * Project: Ingenious
 * Repository: https://gitee.com/ingenstream/ingenious
 */

namespace ingenious\service;

use ingenious\core\Execution;
use ingenious\core\ServiceContext;
use ingenious\db\ProcessDefine;
use ingenious\db\ProcessInstance;
use ingenious\db\ProcessTask;
use ingenious\db\ProcessTaskActor;
use ingenious\enums\CountersignTypeEnum;
use ingenious\enums\ProcessConst;
use ingenious\enums\ProcessEventTypeEnum;
use ingenious\enums\ProcessTaskPerformTypeEnum;
use ingenious\enums\ProcessTaskStateEnum;
use ingenious\enums\ProcessTaskTypeEnum;
use ingenious\event\ProcessEventService;
use ingenious\ex\LFlowException;
use ingenious\interface\LoginUserHolderInterface;
use ingenious\libs\base\BaseService;
use ingenious\libs\utils\AssertHelper;
use ingenious\libs\utils\DateTimeHelper;
use ingenious\libs\utils\Dict;
use ingenious\libs\utils\Logger;
use ingenious\libs\utils\ModelUtils;
use ingenious\libs\utils\PageParam;
use ingenious\libs\utils\ProcessFlowUtils;
use ingenious\libs\utils\StringHelper;
use ingenious\model\CustomModel;
use ingenious\model\NodeModel;
use ingenious\model\ProcessModel;
use ingenious\model\TaskModel;
use ingenious\parser\NodeParser;
use ingenious\scheduling\IScheduler;
use ingenious\service\interface\ProcessTaskServiceInterface;
use think\facade\Db;
use ingenious\interface\AssignmentDefaultInterface;

class ProcessTaskService extends BaseService implements ProcessTaskServiceInterface
{

    protected function setModel(): string
    {
        return ProcessTask::class;
    }

    public function create($param): bool
    {
        unset($param->id);
        $processTask = new ProcessTask();
        ModelUtils::copyProperties($param, $processTask);
        return $processTask->save();
    }

    public function update($param): bool
    {
        AssertHelper::notNull($param->id ?? '', '参数ID不能为空');
        $processTask = $this->get($param->id);
        ModelUtils::copyProperties($param, $processTask);
        return $processTask->save();
    }

    public function findById(string|int $id): ?ProcessTask
    {
        $processTask = $this->get($id);
        if ($processTask != null) {
            $processTask->set('ext', $processTask->getData('variable'));
            $processTask->set('variable', json_encode($processTask->getData('variable')));
        }
        return $processTask;
    }

    public function saveProcessTask(ProcessTask $processTask): void
    {
        $processTask->save();
    }

    public function updateProcessTask(ProcessTask $processTask): void
    {
        $processTask->save();
    }

    public function getDoingTaskList(string $processInstanceId, string|array $taskNames): ?array
    {
        $map1 = [
            'process_instance_id' => $processInstanceId,
            'task_state'          => ProcessTaskStateEnum::DOING[0],
        ];
        if (!empty($taskNames)) {
            $map1['task_name'] = $taskNames;
        }
        return $this->selectList($map1, '*', 0, 0, '', [], true)->all();
    }

    public function getDoneTaskList(string $processInstanceId, string|array $taskNames): ?array
    {
        $map1 = [
            'process_instance_id' => $processInstanceId,
            'not_in_task_state'   => ProcessTaskStateEnum::DOING[0],
        ];
        if (!empty($taskNames)) {
            $map1['task_name'] = $taskNames;
        }
        return $this->selectList($map1, '*', 0, 0, '', [], true)->all();
    }

    public function finishProcessTask(string $processTaskId, string $operator, Dict $args): void
    {
        $processTask = $this->get($processTaskId);
        AssertHelper::notNull($processTask, '任务节点不存在或被删除');
        $processTask->set('task_state', ProcessTaskStateEnum::FINISHED[0]);
        $processTask->set('update_time', time());
        $processTask->set('update_user', $operator);
        $processTask->set('operator', $operator);
        $processTask->set('finish_time', time());
        $newArgs = new Dict();
        if (!empty($processTask->getData('variable'))) {
            $newArgs->putAll((object)[]);
        }
        $newArgs->putAll($args->getAll());
        if (StringHelper::equalsIgnoreCase(ProcessConst::AUTO_ID, $operator)) {
            //获取当前系统登录用户
            $login = ServiceContext::findFirst(LoginUserHolderInterface::class);
            AssertHelper::notNull($login, '未找到当前系统登录用户接口');
            $sysUserId = $login->getUserId();
            AssertHelper::notNull($sysUserId, '获取当前系统登录用户失败');
            ProcessFlowUtils::addUserInfoToArgs($sysUserId, $newArgs);
        } else {
            ProcessFlowUtils::addUserInfoToArgs($operator, $newArgs);
        }
        $processTask->set('variable', $newArgs);
        $processTask->save();
        //发布流程结束结束事件
        ProcessEventService::publishNotification(ProcessEventTypeEnum::PROCESS_TASK_END[0], $processTask->getData('id'));
    }

    public function abandonProcessTask(string $processTaskId, string $operator, Dict $args): void
    {
        $processTask = $this->get($processTaskId);
        AssertHelper::notNull($processTask, '任务节点不存在或被删除');
        $processTask->set('task_state', ProcessTaskStateEnum::ABANDON[0]);
        $processTask->set('update_time', time());
        $processTask->set('update_user', $operator);
        $processTask->set('operator', $operator);
        $processTask->set('finish_time', time());
        $newArgs = new Dict();
        $newArgs->putAll($processTask->getData('variable'));
        $newArgs->putAll($args->getAll());
        if (StringHelper::equalsIgnoreCase(ProcessConst::AUTO_ID, $operator)) {
            ProcessFlowUtils::addUserInfoToArgs(ProcessConst::AUTO_ID, $newArgs);
        } else {
            ProcessFlowUtils::addUserInfoToArgs($operator, $newArgs);
        }
        $processTask->set('variable', $newArgs);
        $processTask->save();
        //发布流程结束结束事件
        ProcessEventService::publishNotification(ProcessEventTypeEnum::PROCESS_TASK_END[0], $processTask->getData('id'));
    }

    public function createTask(TaskModel $taskModel, Execution $execution): ?array
    {
        $processTaskList = [];
        $processTask     = new ProcessTask();
        $processTask->set('perform_type', ProcessTaskPerformTypeEnum::NORMAL[0]);
        $processTask->set('task_name', $taskModel->getName());
        $processTask->set('display_name', $taskModel->getDisplayName());
        $processTask->set('task_state', ProcessTaskStateEnum::DOING[0]);
        $processTask->set('process_instance_id', $execution->getProcessInstanceId());
        $execution->getArgs()->put(ProcessConst::IS_FIRST_TASK_NODE, ProcessFlowUtils::isFistTaskName($execution->getProcessModel(), $taskModel->getName()));
        //添加消息提醒间隔时间是否自动执行参数到变量
        $execution->getArgs()->put(NodeParser::REMINDER_TIME_KEY, $taskModel->getReminderTime());
        $execution->getArgs()->put(NodeParser::REMINDER_REPEAT_KEY, $taskModel->getReminderRepeat());
        $execution->getArgs()->put(NodeParser::AUTH_EXECUTE_KEY, $taskModel->getAutoExecute());

        $processTask->set('variable', $execution->getArgs()->getAll());
        $processTask->set('create_time', time());
        $processTask->set('update_time', time());
        $processTask->set('task_parent_id', $execution->getProcessTaskId());
        $expireTime = $taskModel->getExpireTime();
        if (!empty($expireTime)) {
            $dateTime = ProcessFlowUtils::processTime($expireTime, $execution->getArgs());
            if ($dateTime !== null) {
                $processTask->set('expire_time', $dateTime->getTimestamp());
            }
        }
        $processTask->save();
        $execution->setProcessTask($processTask);
        $processTaskList[] = $processTask;
        $this->addTaskActor($processTask->getData('id'), $this->getTaskActors($taskModel, $execution));
        return $processTaskList;

    }

    public function getById(string $id): ?ProcessTask
    {
        return $this->get($id);
    }

    public function addTaskActor($processTaskId, array|string $actors,): void
    {
        if (empty($actors)) {
            return;
        }
        $actors                  = is_array($actors) ? $actors : explode(',', $actors);
        $processTaskActorService = new ProcessTaskActorService();
        $dbActors                = $processTaskActorService->getColumn(['process_task_id' => $processTaskId], 'actor_id');
        $filteredActors          = array_filter($actors, function ($actor) use ($dbActors) {
            return !in_array($actor, $dbActors);
        });
        foreach ($filteredActors as $actor) {
            $insertData = (object)[
                'process_task_id' => $processTaskId,
                'actor_id'        => (string)$actor,
                'create_time'     => time(),
            ];
            $processTaskActorService->create($insertData);
        }
    }

    public function addCandidateActor(string $processTaskId, array|string $actors): void
    {
        if (empty($actors)) {
            return;
        }
        $processTask = $this->get($processTaskId);
        if (empty($processTask)) {
            return;
        }
        // 主要调整流程变量中的参与者
        $processInstanceService = new ProcessInstanceService();
        $processInstance        = $processInstanceService->get($processTask->getData('process_instance_id'));
        $prefix                 = ProcessConst::COUNTERSIGN_VARIABLE_PREFIX . $processTask->getData('task_name') . "_";
        if ($processInstance != null) {
            $args = ProcessFlowUtils::variableToDict($processInstance->getData('variable'));
            // 会签办理人列表
            $operatorList = $args->get($prefix . ProcessConst::COUNTERSIGN_OPERATOR_LIST, []);
            $operatorList = array_unique(array_merge($operatorList, is_array($actors) ? $actors : explode(',', $actors)));
            $args->put($prefix . ProcessConst::COUNTERSIGN_OPERATOR_LIST, $operatorList);
            $processInstance->set('variable', $args->getAll());
            $processInstance->save();
        }
    }

    public function removeTaskActor(string $processTaskId, array|string $actors): void
    {
        if (empty($processTaskId) || empty($actors)) {
            return;
        }
        $processTaskActor = new ProcessTaskActor();
        $processTaskActor->where('process_task_id', $processTaskId)
            ->whereIn('actor_id', is_array($actors) ? implode(',', $actors) : $actors)
            ->delete();

    }

    public function isAllowed(ProcessTask $task, string $operator): bool
    {
        // 执行者为超级管理员或自动执行用户
        if (strcasecmp(ProcessConst::ADMIN_ID, $operator) === 0 || strcasecmp(ProcessConst::AUTO_ID, $operator) === 0) {
            return true;
        }
        // 任务操作者==执行者
        if (strcasecmp($task->getData('operator'), $operator) === 0) {
            return true;
        }
        // 任务参考者==执行者
        $actorsList = $this->getTaskActor($task->getData('id'));
        if (!empty($actorsList)) {
            return in_array($operator, $actorsList);
        }
        return false;
    }

    public function getTaskActor($processTaskId): array
    {
        $processTaskActorService = new ProcessTaskActorService();
        return $processTaskActorService->getColumn(['process_task_id' => $processTaskId], 'actor_id');
    }

    public function getTaskActors(TaskModel $model, Execution $execution): array
    {

        // 1 参与者处理优先级下一节点处理人
        $nextNodeOperator = $execution->getArgs()->get(ProcessConst::NEXT_NODE_OPERATOR);
        if (!empty($nextNodeOperator)) {
            return is_array($nextNodeOperator) ? $nextNodeOperator : explode(',', $nextNodeOperator);
        }
        $result   = [];
        $assignee = $model->getAssignee();
        // 2 参与人处理
        if (!empty($assignee)) {
            // 2.1参与人包含key优先表单处理
            // 多个使用英文逗号分割
            $assigneeArr = explode(",", $assignee);
            foreach ($assigneeArr as $assigneeItem) {
                // 如果args中存在assigneeArr[i]为key的数据
                $argsArr = (array)$execution->getArgs()->getAll();
                if (array_key_exists($assigneeItem, $argsArr)) {
                    $result[] = $argsArr[$assigneeItem];
                } else {
                    // 如果args中不存在assigneeArr[i]为key的数据
                    $result[] = $assigneeItem;
                }
            }
        } else {
            // 2.2 参与人处理再没有输入参与人跟包含key情况
            $assignmentHandler = $model->getAssignmentHandler();
            if (!empty($assignmentHandler)) {
                $assignmentHandlerObj = new $assignmentHandler();
                $result               = array_merge($result, $assignmentHandlerObj->assign($model, $execution));
            } else {
                //默认参与者处理类
                $assigneeList = ServiceContext::findList(AssignmentDefaultInterface::class);
                foreach ($assigneeList as $assigneeObj) {
                    $result = array_merge($result, $assigneeObj->assign($model, $execution));
                }
            }
        }

        //3 参与人表单key 独立与参与者共存建议前端控制
        $assignee = $model->getAssigneeFormKey();
        if (!empty($assignee)) {
            $assigneeArr = explode(",", $assignee);
            foreach ($assigneeArr as $assigneeItem) {
                // 如果args中存在assigneeArr[i]为key的数据
                $argsArr = (array)$execution->getArgs()->getAll();
                if (array_key_exists($assigneeItem, $argsArr)) {
                    $result[] = $argsArr[$assigneeItem];
                } else {
                    // 如果args中不存在assigneeArr[i]为key的数据
                    $result[] = $assigneeItem;
                }
            }
        }
        return $result;
    }

    public function todoList(object $param): array
    {

        AssertHelper::notNull($param->actor_id ?? '', '参数actor_id 不能为空');
        [$page, $limit] = PageParam::getPageValue($param);
        $processTaskActorTable = ProcessTaskActor::getTableName();
        $processTaskTable      = ProcessTask::getTableName();
        $processInstanceTable  = ProcessInstance::getTableName();
        $processDefineTable    = ProcessDefine::getTableName();
        $map1                  = [
            ['pt.task_state', '=', ProcessTaskStateEnum::DOING[0]],
            ['pta.actor_id', '=', $param->actor_id],
        ];
        if (isset($param->business_no) && !empty($param->business_no)) {
            $map1[] = ['pi.business_no', '=', $param->business_no];
        }
        if (isset($param->task_name) && !empty($param->task_name)) {
            $map1[] = ['pt.task_name', '=', $param->task_name];
        }
        if (isset($param->display_name) && !empty($param->display_name)) {
            $map1[] = ['pt.display_name', 'like', $param->display_name . '%'];
        }
        if (isset($param->process_define_name) && !empty($param->process_define_name)) {
            $map1[] = ['pd.name', '=', $param->process_define_name];
        }
        if (isset($param->process_define_display_name) && !empty($param->process_define_display_name)) {
            $map1[] = ['pd.display_name', 'like', $param->process_define_display_name . '%'];
        }
        $list  = Db::table($processTaskActorTable)
            ->alias('pta')
            ->where($map1)
            ->field('pta.*, pt.task_name,pt.display_name,pt.task_type,pt.perform_type,pt.task_state,pt.variable as ext,pt.create_time,pt.update_time, pi.id as process_instance_id, pd.id as process_define_id,pi.create_time as instance_create_time,pi.variable as instance_ext,pi.business_no,pd.name as process_define_name,pd.display_name as process_define_display_name,pd.description as process_define_description,pd.content as define_ext')
            ->join([$processTaskTable => 'pt'], 'pta.process_task_id = pt.id')
            ->join([$processInstanceTable => 'pi'], 'pt.process_instance_id = pi.id')
            ->join([$processDefineTable => 'pd'], 'pi.process_define_id = pd.id')
            ->order('pta.create_time', 'desc')
            ->page($page, $limit)
            ->select()
            ->toArray();
        $count = Db::table($processTaskActorTable)
            ->alias('pta')
            ->where($map1)
            ->join([$processTaskTable => 'pt'], 'pta.process_task_id = pt.id')
            ->join([$processInstanceTable => 'pi'], 'pt.process_instance_id = pi.id')
            ->join([$processDefineTable => 'pd'], 'pi.process_define_id = pd.id')
            ->order('pta.create_time', 'desc')
            ->count();
        foreach ($list as $key => $value) {
            $list[$key]['ext']                  = json_decode($value['ext']) ?? (object)[];
            $list[$key]['create_date']          = !empty($value['create_time']) ? DateTimeHelper::timestampToString($value['create_time']) : '';
            $list[$key]['update_date']          = !empty($value['update_time']) ? DateTimeHelper::timestampToString($value['update_time']) : '';
            $list[$key]['instance_create_date'] = !empty($value['instance_create_time']) ? DateTimeHelper::timestampToString($value['instance_create_time']) : '';
            $list[$key]['instance_ext']         = json_decode($value['instance_ext']) ?? (object)[];
            $list[$key]['define_ext']           = json_decode($value['define_ext']) ?? (object)[];
        }
        return compact('list', 'count');
    }

    public function doneList(object $param): array
    {
        AssertHelper::notNull($param->actor_id ?? '', '参数actor_id 不能为空');
        [$page, $limit] = PageParam::getPageValue($param);
        $processTaskActorTable = ProcessTaskActor::getTableName();
        $processTaskTable      = ProcessTask::getTableName();
        $processInstanceTable  = ProcessInstance::getTableName();
        $processDefineTable    = ProcessDefine::getTableName();
        $map1                  = [
            ['pta.actor_id', '=', $param->actor_id],
            ['pt.task_state', 'not in', [ProcessTaskStateEnum::DOING[0], ProcessTaskStateEnum::WITHDRAW[0]]],
            ['pt.operator', '<>', ProcessConst::AUTO_ID],
        ];
        if (isset($param->business_no) && !empty($param->business_no)) {
            $map1[] = ['pi.business_no', '=', $param->business_no];
        }
        if (isset($param->task_name) && !empty($param->task_name)) {
            $map1[] = ['pt.task_name', '=', $param->task_name];
        }
        if (isset($param->display_name) && !empty($param->display_name)) {
            $map1[] = ['pt.display_name', 'like', $param->display_name . '%'];
        }
        if (isset($param->process_define_name) && !empty($param->process_define_name)) {
            $map1[] = ['pd.name', '=', $param->process_define_name];
        }
        if (isset($param->process_define_display_name) && !empty($param->process_define_display_name)) {
            $map1[] = ['pd.display_name', 'like', $param->process_define_display_name . '%'];
        }
        $list  = Db::table($processTaskActorTable)
            ->alias('pta')
            ->where($map1)
            ->field('pta.*, pt.task_name,pt.display_name,pt.task_type,pt.perform_type,pt.task_state,pt.variable as ext,pt.create_time,pt.update_time, pi.id as process_instance_id, pd.id as process_define_id,pi.create_time as instance_create_time,pi.variable as instance_ext,pi.business_no,pd.name as process_define_name,pd.display_name as process_define_display_name,pd.description as process_define_description,pd.content as define_ext')
            ->join([$processTaskTable => 'pt'], 'pta.process_task_id = pt.id')
            ->join([$processInstanceTable => 'pi'], 'pt.process_instance_id = pi.id')
            ->join([$processDefineTable => 'pd'], 'pi.process_define_id = pd.id')
            ->order('pta.create_time', 'desc')
            ->page($page, $limit)
            ->select()
            ->toArray();
        $count = Db::table($processTaskActorTable)
            ->alias('pta')
            ->where($map1)
            ->join([$processTaskTable => 'pt'], 'pta.process_task_id = pt.id')
            ->join([$processInstanceTable => 'pi'], 'pt.process_instance_id = pi.id')
            ->join([$processDefineTable => 'pd'], 'pi.process_define_id = pd.id')
            ->order('pta.create_time', 'desc')
            ->count();
        foreach ($list as $key => $value) {
            $list[$key]['ext']                  = json_decode($value['ext']) ?? (object)[];
            $list[$key]['create_date']          = !empty($value['create_time']) ? DateTimeHelper::timestampToString($value['create_time']) : '';
            $list[$key]['update_date']          = !empty($value['update_time']) ? DateTimeHelper::timestampToString($value['update_time']) : '';
            $list[$key]['finish_date']          = !empty($value['finish_time']) ? DateTimeHelper::timestampToString($value['finish_time']) : '';
            $list[$key]['instance_create_date'] = !empty($value['instance_create_time']) ? DateTimeHelper::timestampToString($value['instance_create_time']) : '';
            $list[$key]['instance_ext']         = json_decode($value['instance_ext']) ?? (object)[];
            $list[$key]['define_ext']           = json_decode($value['define_ext']) ?? (object)[];
        }
        return compact('list', 'count');
    }

    public function rejectTask(ProcessModel $model, ProcessTask $currentTask): ?ProcessTask
    {
        $taskParentId = $currentTask->getData('task_parent_id');
        AssertHelper::notNull($taskParentId, '上一步任务ID为空，无法驳回至上一步处理');
        $current = $model->getNode($currentTask->getData('task_name'));
        $history = $this->get($taskParentId);

        $parent = $model->getNode($history->getData('task_name'));
        if (!NodeModel::canRejected($current, $parent)) {
            throw new LFlowException("无法驳回至上一步处理，请确认上一步骤并非fork、join、suprocess以及会签任务", '', 99999999);
        }
        $task = new ProcessTask();
        ModelUtils::copyProperties((object)$history->toArray(), $task);
        $task->set('id', null);
        $task->set('task_tate', ProcessTaskStateEnum::DOING[0]);
        $task->set('create_time', null);
        $task->set('create_user', null);
        $task->set('update_time', null);
        $task->set('update_user', null);
        $task->set('finish_time', null);
        $operator    = $history->getData('operator');
        $hisVariable = ProcessFlowUtils::variableToDict($history->getData('variable'));
        if ($hisVariable->get(ProcessConst::IS_FIRST_TASK_NODE, false)) {
            // 第一个节点的操作人从任务变量中获取
            $operator = $hisVariable->get(ProcessConst::USER_USER_ID);
        }
        $task->set('variable', $hisVariable->getAll());
        $task->set('operator', $operator);
        $task->set('expire_time', $history->getData('expire_time'));
        $this->saveProcessTask($task);
        $this->addTaskActor($task->getData('id'), $task->getData('operator'));
        return $task;
    }

    public function jumpAbleTaskNameList(string $processInstanceId): array
    {
        $taskNames       = [];
        $result          = [];
        $processTaskList = $this->getDoneTaskList($processInstanceId, '');
        $processTaskList = array_filter($processTaskList, function ($task) {
            return !(ProcessTaskPerformTypeEnum::COUNTERSIGN[0] === $task->getData('perform_type'));
        });
        foreach ($processTaskList as $processTask) {
            $taskName = $processTask->getData('task_name');
            if (!in_array($taskName, $taskNames)) {
                $taskNames[] = $taskName;
                $result[]    = (object)['label' => $processTask->getData('display_name'), 'value' => $processTask->getData('task_name'), 'ext' => null];
            }
        }
        return $result;
    }

//    public function candidatePage($query): object
//    {
//
//    }

    public function candidatePage(Dict $query): object|array
    {
        $processTaskId   = $query->get(ProcessConst::PROCESS_TASK_ID_KEY);
        $processTask     = null;
        $processInstance = null;
        $processModel    = null;
        if ($processTaskId) {
            $processTask = $this->get($processTaskId);
        }
        if ($processTask) {
            $processInstanceService = new ProcessInstanceService();
            $processInstance        = $processInstanceService->get($processTask->getData('process_instance_id'));
        }
        if ($processInstance) {
            $processDefineService = new ProcessDefineService();
            $processModel         = $processDefineService->getProcessModel($processInstance->getData('process_define_id'));
        }
        $candidateList = null;
        if (!empty($processModel)) {
            $candidateList = $processModel->getNextTaskModelCandidates($processTask->getData('task_name'));
        }
        //通过id in查询用户列表
        $list  = [];
        $count = 0;
        return compact('list', 'count');
    }

    public function createCountersignTask(TaskModel $taskModel, Execution $execution): ProcessTask|array|null
    {
        $processTaskList  = [];
        $taskActors       = $this->getTaskActors($taskModel, $execution);
        $createTaskActors = [];

        if ($taskModel->getCountersignType()[0] === CountersignTypeEnum::PARALLEL[0]) {
            // 并行：一个参与者一个任务，同时创建
            $createTaskActors = $taskActors;
            // 追加会签类型参数
            $execution->getArgs()->put(ProcessConst::COUNTERSIGN_VARIABLE_PREFIX . ProcessConst::COUNTERSIGN_TYPE, CountersignTypeEnum::PARALLEL[0]);
        } else {
            // 串行：一个参与者一个任务，顺序创建，默认只创建第一个，其他等执行完一个后再创建
            $prefix = ProcessConst::COUNTERSIGN_VARIABLE_PREFIX . $taskModel->getName() . "_";
            // 获取循环计数器，默认值为-1
            $loopCounter = $execution->getArgs()->get($prefix . ProcessConst::LOOP_COUNTER, -1);
            if ($loopCounter === -1) {
                $createTaskActors[] = $taskActors[0];
            } else {
                $createTaskActors[] = $taskActors[$loopCounter + 1];
                // 更新计数器为最后的下标值
                $execution->getArgs()->put($prefix . ProcessConst::LOOP_COUNTER, $loopCounter + 1);
            }
            // 追加会签类型参数
            $execution->getArgs()->put(ProcessConst::COUNTERSIGN_VARIABLE_PREFIX . ProcessConst::COUNTERSIGN_TYPE, CountersignTypeEnum::SEQUENTIAL[0]);
        }

        foreach ($createTaskActors as $taskActor) {
            $processTask = new ProcessTask();
            $processTask->set('perform_type', ProcessTaskPerformTypeEnum::COUNTERSIGN[0]);
            $processTask->set('task_name', $taskModel->getName());
            $processTask->set('display_name', $taskModel->getDisplayName());
            $processTask->set('task_state', ProcessTaskStateEnum::DOING[0]);
            $processTask->set('task_type', $taskModel->getTaskType()[0] ?? 0);
            $processTask->set('process_instance_id', $execution->getProcessInstanceId());
            $execution->getArgs()->put(ProcessConst::IS_FIRST_TASK_NODE, ProcessFlowUtils::isFistTaskName($execution->getProcessModel(), $taskModel->getName()));
            $processTask->set('Variable', $execution->getArgs()->getAll());
            $processTask->set('task_parent_id', $execution->getProcessTaskId() ?? 0);
            $expireTime = $taskModel->getExpireTime();
            if (!empty($expireTime)) {
                $processTask->set('expire_time', ProcessFlowUtils::processTime($expireTime, $execution->getArgs()));
            }
            $processTask->save();
            $execution->setProcessTask($processTask);
            Logger::debug("创建会签任务" . $processTask->getData('task_name') . "," . $processTask->getData('display_name'));
            $processTaskList[] = $processTask;
            $this->addTaskActor($processTask->getData('id'), [$taskActor]);
        }

        $processInstanceService = $execution->getEngine()->processInstanceService();
        // 更新会签变量到流程实例参数中
        $processInstanceService->updateCountersignVariable($taskModel, $execution, $taskActors);
        return $processTaskList;
    }

    public function history(Execution $execution, CustomModel $model): ?ProcessTask
    {
        $processTask = new ProcessTask();
        $processTask->set('process_instance_id', $execution->getProcessInstanceId());
        $processTask->set('create_time', time());
        $processTask->set('finish_time', time());
        $processTask->set('display_name', $model->getDisplayName());
        $processTask->set('task_name', $model->getNmae());
        $processTask->set('task_state', ProcessTaskStateEnum::FINISHED[0]);
        $processTask->set('task_type', ProcessTaskTypeEnum::RECORD[0]);
        $processTask->set('parent_id', $execution->getProcessTaskId());
        $processTask->set('variable', $execution->getArgs());
        return $processTask::create($processTask->toArray());
    }

}
