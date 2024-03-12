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

namespace ingenious\service;

use ingenious\core\Execution;
use ingenious\core\ProcessEngines;
use ingenious\db\ProcessCcInstance;
use ingenious\db\ProcessDefine;
use ingenious\db\ProcessInstance;
use ingenious\db\virtual\HighLightVirtual;
use ingenious\domain\DefaultNoGenerator;
use ingenious\enums\ProcessConst;
use ingenious\enums\ProcessInstanceStateEnum;
use ingenious\enums\ProcessSubmitTypeEnum;
use ingenious\enums\ProcessTaskStateEnum;
use ingenious\enums\YourEnum;
use ingenious\libs\base\BaseService;
use ingenious\libs\utils\ArrayHelper;
use ingenious\libs\utils\AssertHelper;
use ingenious\libs\utils\Dict;
use ingenious\libs\utils\ModelUtils;
use ingenious\libs\utils\PageParam;
use ingenious\libs\utils\ProcessFlowUtils;
use ingenious\model\DecisionModel;
use ingenious\model\EndModel;
use ingenious\model\TaskModel;
use ingenious\service\interface\ProcessInstanceServiceInterface;

class ProcessInstanceService extends BaseService implements ProcessInstanceServiceInterface
{

    protected function setModel(): string
    {
        return ProcessInstance::class;
    }

    public function create($param): bool
    {
        unset($param->id);
        $processInstance = new ProcessInstance();
        ModelUtils::copyProperties($param, $processInstance);
        return $processInstance->save();
    }

    public function update(object $param): bool
    {
        AssertHelper::notNull($param->id ?? '', '参数ID不能为空');
        $processInstance = $this->get($param->id);
        ModelUtils::copyProperties($param, $processInstance);
        return $processInstance->save();
    }

    public function page(object $param): array
    {
        $where = ArrayHelper::paramsFilter($param, [
            ['parent_id', ''],
            ['process_define_id', ''],
            ['state', ''],
            ['parent_node_name', ''],
            ['business_no', ''],
            ['operator', ''],
        ]);
        [$page, $limit] = PageParam::getPageValue($param);
        $list  = $this->selectList($where, '*', $page, $limit, 'create_time asc', ['processDefine'], true)->toArray();
        $count = $this->count($where);
        foreach ($list as $key => $value) {
            $list[$key]['process_name'] = $value['processDefine']['display_name'] ?? '';
            $list[$key]['ext']          = $value['variable'];
            $list[$key]['form_data']    = ProcessFlowUtils::filterObjectByPrefix((object)$value['variable'], 'f_');
            $list[$key]['variable']     = json_encode($value['variable']);
            unset($value['processDefine']);
        }
        return compact('list', 'count');
    }

    public function findById(string $id): ?ProcessInstance
    {
        AssertHelper::notNull($id, '参数ID不能为空');
        $processInstance = $this->get($id, ['*'], ['processDefine']);
        if ($processInstance != null) {
            $processDefine = $processInstance->getData('processDefine');
            $content       = ArrayHelper::arrayToObject($processDefine->getData('content') ?? []);
            $processInstance->set('version', $processDefine->getData('version'));
            $processInstance->set('json_object', $content);
            $processInstance->set('ext', $processInstance->getData('variable'));
            $processInstance->set('form_data', ProcessFlowUtils::filterObjectByPrefix($processInstance->getData('variable'), 'f_'));
            $processInstance->set('variable', json_encode($processInstance->getData('variable')));
            $processInstance->hidden(['processDefine']);
        }

        return $processInstance;
    }

    public function finishProcessInstance(string $processInstanceId): void
    {
        $processInstance = $this->get($processInstanceId);
        AssertHelper::notNull($processInstance, '实例不存在或被删除');
        $processInstance->set('state', ProcessInstanceStateEnum::FINISHED[0]);
        $processInstance->save();
    }

    public function createProcessInstance(ProcessDefine $processDefine, string $operator, Dict $args, string|null $parentId = '', string|null $parentNodeName = ''): ProcessInstance
    {
        $processInstance = new ProcessInstance();
        $processInstance->set('parent_id', $parentId);
        $processInstance->set('parent_node_name', $parentNodeName);
        $processInstance->set('process_define_id', $processDefine->getData('id'));
        $processInstance->set('operator', $operator);
        $processInstance->set('state', ProcessInstanceStateEnum::DOING[0]);
        // 业务流水号从流程变量中获取
        $processInstance->set('business_no', $args->{ProcessConst::BUSINESS_NO} ?? '');
        if (empty($args->get(ProcessConst::BUSINESS_NO))) {
            //外面没有传单据编号使用内置默认编号生成器
            $processInstance->set('business_no', (new DefaultNoGenerator())->generate(null));
        }
        // 追加用户信息到参数
        ProcessFlowUtils::addUserInfoToArgs($operator, $args);
        // 追加自动构造标题
        ProcessFlowUtils::addAutoGenTitle($processDefine->getData('display_name'), $args);
        $processInstance->set('variable', $args->getAll());
        return $this->saveProcessInstance($processInstance);
    }

    public function addVariable(string $processInstanceId, Dict $args): void
    {
        $processInstance = $this->get($processInstanceId);
        AssertHelper::notNull($processInstance, '流程实例不存在');
        $variable = $processInstance->getData('variable') ?? (object)[];
        $newDict  = new Dict();
        $newDict->putAll($variable);
        $newDict->putAll($args->getAll());
        $processInstance->set('variable', $newDict->getAll());
        $processInstance->save();
    }

    public function removeVariable(string $processInstanceId, string|array $keys): void
    {
        $processInstance = $this->get($processInstanceId);
        AssertHelper::notNull($processInstance, '流程实例不存在');
        $variable = $processInstance->getData('variable') ?? (object)[];
        $oldDict  = new Dict();
        $oldDict->putAll($variable);
        foreach (is_array($keys) ? $keys : explode(',', $keys) as $value) {
            $oldDict->remove($value);
        }
        $processInstance->set('variable', $oldDict->getAll());
        $processInstance->save();
    }

    public function saveProcessInstance(ProcessInstance $processInstance): ?ProcessInstance
    {
        $processInstance->set('create_time', time());
        $processInstance->set('update_time', time());
        return $processInstance::create($processInstance->toArray());
    }

    public function interrupt(string $processInstanceId, string $operator): void
    {
        // 1. 将该流程实例产生的任务状态修改为终止
        $processTaskService = new ProcessTaskService();
        $map1               = [
            'id'         => $processInstanceId,
            'task_state' => ProcessTaskStateEnum::DOING[0],
        ];
        $processTask        = $processTaskService->get($map1);
        if (!empty($processTask)) {
            $processTask->set('task_state', ProcessTaskStateEnum::INTERRUPT[0]);
            $processTask->set('update_time', time());
            $processTask->set('update_user', $operator);
            $processTask->save();
        }
        // 2. 将该流程实例状态修改为终止
        $map2            = [
            'id'    => $processInstanceId,
            'state' => ProcessInstanceStateEnum::DOING[0],
        ];
        $processInstance = $this->get($map2);
        if (!empty($processInstance)) {
            $processInstance->set('state', ProcessInstanceStateEnum::INTERRUPT[0]);
            $processInstance->set('update_time', time());
            $processInstance->set('update_user', $operator);
            $processInstance->save();
        }
    }

    public function resume(string $processInstanceId, string $operator): void
    {
        // 1. 更新流程实例状态为进行中
        $processInstance = $this->get($processInstanceId);
        AssertHelper::notNull($processInstance, '唤醒失败，流程实例不存在');
        $processInstance->set('state', ProcessInstanceStateEnum::DOING[0]);
        $processInstance->set('update_time', time());
        $processInstance->set('update_user', $operator);
        $processInstance->save();
        // 2.被终止的任务状态修改为进行中
        $processTaskService = new ProcessTaskService();
        $map1               = [
            'process_instance_id' => $processInstanceId,
            'task_state'          => ProcessTaskStateEnum::INTERRUPT[0],
        ];
        $processTask        = $processTaskService->get($map1);
        AssertHelper::notNull($processTask, '唤醒失败，查找终止任务失败');
        $processTask->set('task_state', ProcessTaskStateEnum::DOING[0]);
        $processInstance->set('update_time', time());
        $processInstance->set('update_user', $operator);
        $processInstance->save();
    }

    public function cascadeDelete(string $processInstanceId, ?string $operator = null): void
    {
        // 1. 将该流程实例状态修改为撤回
        $map1            = [
            ['id', '=', $processInstanceId],
            ['state', 'notIn', [ProcessInstanceStateEnum::DOING[0], ProcessInstanceStateEnum::PENDING[0], ProcessInstanceStateEnum::FINISHED[0]]],
        ];
        $processInstance = $this->get($map1);
        AssertHelper::notNull($processInstance, '流程实例不存在或已完成，撤回失败');
        if ($processInstance != null) {
            //1.删除task
            $map1               = ['process_instance_id' => $processInstance->getData('id')];
            $processTaskService = new ProcessTaskService();
            $taskList           = $processTaskService->selectList($map1, '*', 0, 0, '', [], true);
            //2.删除actions
            foreach ($taskList as $task) {
                $map2                    = ['process_task_id' => $task->getData('id')];
                $processTaskActorService = new ProcessTaskActorService();
                $actorList               = $processTaskActorService->selectList($map2, '*', 0, 0, '', [], true);
                foreach ($actorList as $actor) {
                    $actor->delete();
                }
                $task->delete();
            }
        }
        $processInstance->delete();
    }

    public function pending(string $processInstanceId, string $operator): void
    {
        // 1. 将该流程实例产生的任务状态修改为挂起
        $processTaskService = new ProcessTaskService();
        $map1               = [
            'process_instance_id' => $processInstanceId,
            'task_state'          => ProcessTaskStateEnum::DOING[0],
        ];
        $processTask        = $processTaskService->get($map1);
        AssertHelper::notNull($processTask, '挂起失败，没有进行中的任务');
        $processTask->set('task_state', ProcessTaskStateEnum::PENDING[0]);
        $processTask->set('update_time', time());
        $processTask->set('update_user', $operator);
        $processTask->save();
        // 2. 将该流程实例状态修改为挂起
        $map2            = [
            'id'    => $processInstanceId,
            'state' => ProcessInstanceStateEnum::DOING[0],
        ];
        $processInstance = $this->get($map2);
        AssertHelper::notNull($processInstance, '挂起失败，流程实例不存在');
        $processInstance->set('state', ProcessInstanceStateEnum::PENDING[0]);
        $processInstance->set('update_time', time());
        $processInstance->set('update_user', $operator);
        $processInstance->save();
    }

    public function activate(string $processInstanceId, string $operator): void
    {
        // 1. 更新流程实例状态为进行中
        $processInstance = $this->get($processInstanceId);
        AssertHelper::notNull($processInstance, '激活失败，流程实例不存在');
        $processInstance->set('state', ProcessInstanceStateEnum::PENDING[0]);
        $processInstance->set('update_time', time());
        $processInstance->set('update_user', $operator);
        $processInstance->save();
        // 2.被终止的任务状态修改为进行中
        $processTaskService = new ProcessTaskService();
        $map1               = [
            'process_instance_id' => $processInstanceId,
            'task_state'          => ProcessTaskStateEnum::INTERRUPT[0],
        ];
        $processTask        = $processTaskService->get($map1);
        AssertHelper::notNull($processTask, '挂起失败，没有进行中的任务');
        $processTask->set('task_state', ProcessTaskStateEnum::DOING[0]);
        $processTask->set('update_time', time());
        $processTask->set('update_user', $operator);
        $processTask->save();
    }

    public function updateProcessInstance(ProcessInstance $processInstance): void
    {
        $processInstance->set('update_time', time());
        $processInstance->save();
    }

    public function getById(string $id): ?ProcessInstance
    {
        return $this->get($id);
    }

    public function startAndExecute(string $processDefineId, Dict $args): void
    {
        $operator        = $args->get(ProcessConst::USER_USER_ID);
        $processEngines  = new ProcessEngines();
        $processInstance = $processEngines->startProcessInstanceById($processDefineId, $operator, $args);
        $processTaskList = $processEngines->processTaskService()->getDoingTaskList($processInstance->getData('id'), '');

        // 取任务自动执行
        foreach ($processTaskList as $processTask) {
            $args->put(ProcessConst::SUBMIT_TYPE, ProcessSubmitTypeEnum::APPLY[0]);
            $processEngines->executeProcessTask($processTask->getData('id'), ProcessConst::AUTO_ID, $args);
        }
    }

    public function highLight(string $processInstanceId): array
    {
        $vo              = new HighLightVirtual();
        $processInstance = $this->findById($processInstanceId);
        $processEngines  = new ProcessEngines();
        if ($processInstance != null) {
            $processModel = $processEngines->processDefineService()->getProcessModel($processInstance->getData('process_define_id'));
            // 拿到正在进行中的任务==>活跃节点
            $processTaskList = $processEngines->processTaskService()->getDoingTaskList($processInstanceId, '');
            foreach ($processTaskList as $task) {
                if (!$vo->contains('active_node_names', $task->getData('task_name'))) {
                    $vo->add('active_node_names', $task->getData('task_name'));
                    $this->recursionModel($processModel->getStart(), $processInstance, $processTaskList, $task->getData('task_name'), $vo);
                }
            }
            // 拿到非正常结束的流程实例状态值
            $filteredEnums   = array_filter(ProcessInstanceStateEnum::getEnumValues(), function ($enum) {
                return $enum[0] !== ProcessInstanceStateEnum::getCode('DOING') && $enum[0] !== ProcessInstanceStateEnum::getCode('FINISHED');
            });
            $orderStatusList = array_map(function ($enum) {
                return $enum[0];
            }, $filteredEnums);
            //非正常结束特殊处理
            if (in_array($processInstance->getData('state'), $orderStatusList)) {
                $hisProcessTaskList = $processEngines->processTaskService()->getDoneTaskList($processInstanceId, '');
                if (!empty($hisProcessTaskList)) {
                    $lastProcessTask = $hisProcessTaskList[count($hisProcessTaskList) - 1];
                    $nodeModel       = $processModel->getNode($lastProcessTask->getData('task_name'));
                    $this->recursionModel($processModel->getStart(), $processInstance, $hisProcessTaskList, $nodeModel->getOutputs()[0]->getTo(), $vo);
                }
            } else {
                $endModels = $processModel->getModels(EndModel::class);
                foreach ($endModels as $endModel) {
                    $this->recursionModel($processModel->getStart(), $processInstance, $processTaskList, $endModel->getName(), $vo);
                }
            }
        }
        return $vo->toArray();
    }

    public function approvalRecord(string $processInstanceId): array
    {
        $processTaskService = new ProcessTaskService();
        $map1               = [
            'process_instance_id' => $processInstanceId,
            'not_in_task_state'   => implode(',', [ProcessTaskStateEnum::DOING[0], ProcessTaskStateEnum::WITHDRAW[0], ProcessTaskStateEnum::ABANDON[0]]),//不包括“进行中 已撤回 已废弃” 任务
        ];
        $processTaskList    = $processTaskService->selectList($map1, '*', 0, 0, '', [], true);
        foreach ($processTaskList as $task) {
            $task->set('ext', $task->getData('variable'));
        }
        if ($processTaskList == null) {
            return [];
        }
        return $processTaskList->toArray();
    }

    public function withdraw(string $processInstanceId, string $operator): void
    {
        // 1. 将该流程实例状态修改为撤回
        $map1            = [
            'id'    => $processInstanceId,
            'state' => ProcessInstanceStateEnum::DOING[0],
        ];
        $processInstance = $this->get($map1);
        AssertHelper::notNull($processInstance, '流程实例不存在或已完成，撤回失败');
        $processInstance->set('state', ProcessInstanceStateEnum::WITHDRAW[0]);
        $processInstance->set('update_time', time());
        $processInstance->set('update_user', $operator);
        if ($processInstance->save()) {
            // 2. 将该流程实例产生的任务状态修改为撤回
            $processTaskService = new ProcessTaskService();
            $map2               = [
                'process_instance_id' => $processInstanceId,
                'task_state'          => ProcessTaskStateEnum::DOING[0],
            ];
            $upData             = [
                'task_state'  => ProcessTaskStateEnum::WITHDRAW[0],
                'updata_time' => time(),
                'update_user' => $operator,
            ];
            $processTaskService->updated($map2, $upData);
        }
    }

    public function updateCountersignVariable(TaskModel $taskModel, Execution $execution, array $taskActors): void
    {
        /**
         * ● nrOfActivateInstances：当前活动的实例数量，即还没有完成的实例数量
         * ● loopCounter ：循环计数器，办理人在列表中的索引
         * ● nrOfInstances：会签中总共的实例数
         * ● nrOfCompletedInstances：已经完成的实例数量
         * ● operatorList：会签办理人列表
         */
        // 会签任务变量前缀
        $prefix      = ProcessConst::COUNTERSIGN_VARIABLE_PREFIX . $taskModel->getName() . "_";
        $addVariable = new Dict();
        // 更新会签总实例数，nrOfInstances
        $addVariable->put($prefix . ProcessConst::NR_OF_INSTANCES, count($taskActors));
        // 更新会签当前活动实例数，nrOfActivateInstances
        $addVariable->put($prefix . ProcessConst::NR_OF_ACTIVATE_INSTANCES, count($execution->getDoingTaskList()));
        // 更新会签已完成的实例数，nrOfCompletedInstances
        $addVariable->put($prefix . ProcessConst::NR_OF_COMPLETED_INSTANCES, $execution->getArgs()->get($prefix . ProcessConst::NR_OF_COMPLETED_INSTANCES));
        // 更新会签操作人列表 countersignOperatorList
        $addVariable->put($prefix . ProcessConst::COUNTERSIGN_OPERATOR_LIST, $taskActors);
        $this->addVariable($execution->getProcessInstanceId(), $addVariable->getAll());
    }

    /**
     * @inheritDoc
     */
    public function createCCInstance(string $processInstanceId, string $creator, string $actorIds): void
    {
        foreach (explode(',', $actorIds) as $actorId) {
            // 查询数据库中是否存在具有相同流程实例ID和演员ID的记录
            $processCcInstanceService = new ProcessCcInstanceService();
            $count                    = $processCcInstanceService->count(['process_instance_id' => $processInstanceId, 'actor_id' => $actorId], true);
            // 如果不存在记录（count为0）
            if ($count === 0) {
                // 创建新的 ProcessCcInstance 实例
                $processCcInstance = new ProcessCcInstance();
                $processCcInstance->set('process_instance_id', $processInstanceId);
                $processCcInstance->set('actor_id', $actorId);
                $processCcInstance->set('state', YourEnum::NO[0]); // 假设 YourEnumClass 是枚举类，且有一个 NO 常量
                $processCcInstance->set('create_user', $creator);
                $processCcInstance->set('create_time', time());
                $processCcInstance->set('update_user', $creator);
                $processCcInstance->set('update_time', time());
                $processCcInstance->save();
            }
        }
    }

    public function updateCCStatus(string $processInstanceId, string $actorId): void
    {
        AssertHelper::notNull($processInstanceId, '参数 process_instance_id 不能为空');
        AssertHelper::notNull($actorId, '参数 actor_id 不能为空');
        $processCcInstanceService = new ProcessCcInstanceService();
        $map1                     = [
            'process_instance_id' => $processInstanceId,
            'actor_id'            => $actorId,
        ];
        $processCcInstance        = $processCcInstanceService->get($map1);
        AssertHelper::notNull($processCcInstance, '操作异常请刷新后重试');
        $processCcInstance->set('state', YourEnum::YES[0]);
        $processCcInstance->set('update_time', time());
        $processCcInstance->set('update_user', $actorId);
        $processCcInstance->save();
    }

    public function ccInstancePage(object $param): array
    {
        $param->state             = YourEnum::NO[0];
        $processCcInstanceService = new ProcessCcInstanceService();
        return $processCcInstanceService->page($param);
    }

    private function recursionModel($nodeModel, $processInstance, $processTaskList, $taskName, $vo): void
    {
        if ($nodeModel->getName() === $taskName) {
            if ($nodeModel instanceof EndModel) {
                $vo->add('history_node_names', $nodeModel->getName());
            }
            return;
        }
        if (!$vo->contains('history_node_names', $nodeModel->getName())) {
            $vo->add('history_node_names', $nodeModel->getName());

            $filteredOutputs = array_filter($nodeModel->getOutputs(), function ($output) use ($nodeModel, $processInstance, $processTaskList) {
                // 默认取决策节点前面第一个节点为任务节点-待优化
                $defaultDecisionInputModel = null;
                $historyTask               = null;
                if ($nodeModel instanceof DecisionModel) {
                    $defaultDecisionInputModel = $nodeModel->getInputs()[0]->getSource();

                    // 使用查询构建器对模型对象列表进行过滤
                    $filteredTasks = [];
                    foreach ($processTaskList as $hisTask) {
                        if ($defaultDecisionInputModel->getName() === $hisTask->getData('task_name')) {
                            $filteredTasks[] = $hisTask;
                        }
                    }
                    $historyTask = reset($filteredTasks); // 获取筛选后的第一个元素
                }
                $args = new Dict();
                $args->putAll($processInstance->getData('ext'));
                if ($historyTask) {
                    $args->putAll($historyTask->getData('variable'));
                }
                if (!empty($output->getExpr()) && $nodeModel instanceof DecisionModel && $defaultDecisionInputModel !== null) {
                    //表达式处理
                    return true;
                }

                if ($nodeModel instanceof DecisionModel) {
                    $expr = $nodeModel->getExpr();
                    if (!empty($expr)) {
                        return true;
                    }
                }
                return true;
            });
            // 对过滤后的结果进行遍历操作
            foreach ($filteredOutputs as $transitionModel) {
                if (!$vo->contains('history_edge_names', $transitionModel->getName())) {
                    $vo->add('history_edge_names', $transitionModel->getName());
                    $this->recursionModel($transitionModel->getTarget(), $processInstance, $processTaskList, $taskName, $vo);
                }
            }
        }
    }
}
