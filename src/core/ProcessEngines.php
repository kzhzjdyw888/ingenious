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

namespace ingenious\core;

use ingenious\cfg\Configuration;
use ingenious\db\ProcessInstance;
use ingenious\enums\err\LfErrEnum;
use ingenious\enums\ProcessConst;
use ingenious\enums\ProcessInstanceStateEnum;
use ingenious\enums\ProcessTaskStateEnum;
use ingenious\ex\LFlowException;
use ingenious\interface\ConfigurationInterface;
use ingenious\interface\IConfiguration;
use ingenious\interface\ProcessEnginesInterface;
use ingenious\libs\utils\Dict;
use ingenious\libs\utils\ProcessFlowUtils;
use ingenious\libs\utils\StringHelper;
use ingenious\model\EndModel;
use ingenious\model\TaskModel;
use ingenious\model\TransitionModel;
use ingenious\parser\ModelParser;
use ingenious\service\interface\ProcessCcInstanceServiceInterface;
use ingenious\service\interface\ProcessDesignServiceInterface;
use ingenious\service\interface\ProcessTypeServiceInterface;
use ingenious\service\ProcessCcInstanceService;
use ingenious\service\ProcessDefineService;
use ingenious\service\ProcessDesignService;
use ingenious\service\ProcessInstanceService;
use ingenious\service\ProcessTaskService;
use ingenious\service\ProcessTypesService;

class ProcessEngines implements ProcessEnginesInterface
{

    public ?ConfigurationInterface $configuration;
    private ProcessDefineService $processDefineService;
    private ProcessInstanceService $processInstanceService;
    private ProcessTaskService $processTaskService;
    private ProcessCcInstanceServiceInterface $processCcInstanceService;
    private ProcessTypeServiceInterface $processTypesService;
    private ProcessDesignServiceInterface $processDesignService;

    public function __construct(ConfigurationInterface|array $config = [])
    {
        $this->configure($config);
        $this->processTaskService       = ServiceContext::find('processTaskService');
        $this->processDefineService     = ServiceContext::find('processDefineService');
        $this->processInstanceService   = ServiceContext::find('processInstanceService');
        $this->processCcInstanceService = ServiceContext::find('processCcInstanceService');
        $this->processTypesService      = ServiceContext::find('processTypesService');
        $this->processDesignService     = ServiceContext::find('processDesignService');
        return $this;
    }

    public function configure(ConfigurationInterface|array $config = []): void
    {
        if ($config instanceof ConfigurationInterface) {
            $this->configuration = $config;
            ServiceContext::put('configure', $config);
        } else {
            $config              = new Configuration($config);
            $this->configuration = $config;
            ServiceContext::put('configure', $config);
        }
    }

    public function processDefineService(): ProcessDefineService
    {
        return $this->processDefineService;
    }

    public function processInstanceService(): ProcessInstanceService
    {
        return $this->processInstanceService;
    }

    public function processTaskService(): ProcessTaskService
    {
        return $this->processTaskService;
    }

    public function processTypesService(): ?ProcessTypesService
    {
        return $this->processTypesService;
    }

    public function processCcInstanceService(): ProcessCcInstanceService
    {
        return $this->processCcInstanceService;
    }

    public function processDesignService(): ProcessDesignServiceInterface
    {
        return $this->processDesignService;
    }


    public function startProcessInstanceById(string $id, string $operator, Dict $args, string|null $parentId = null, string|null $parentNodeName = null): ProcessInstance
    {
        // 1. 根据流程定义ID查询流程定义文件
        $processDefine = $this->processDefineService->getById($id);
        if (empty($processDefine)) {
            throw new LFlowException('缺少流程定义');
        }
        // 2. 将流程定义文件转成流程模型
        $processModel = $this->processDefineService->processDefineToModel($processDefine);
        // 3. 根据流程定义对象创建流程实例
        $processInstance = $this->processInstanceService->createProcessInstance($processDefine, $operator, $args, $parentId, $parentNodeName);
        // 4. 构建执行参数对象
        $execution = new Execution();
        $execution->setProcessModel($processModel);
        $execution->setProcessInstance($processInstance);
        $execution->setProcessInstanceId($processInstance->getData('id'));
        $execution->setEngine($this);
        $execution->setArgs($args);
        // 5. 拿到开始节点模型，调用其execute方法
        $processModel->getStart()->execute($execution);
        return $processInstance;
    }

    public function executeProcessTask(string $processTaskId, string $operator, Dict $args): array
    {
        $execution = $this->execute($processTaskId, $operator, $args);
        if ($execution == null) {
            return [];
        }
        $processModel = $execution->getProcessModel();
        // 7. 根据流程任务名称获取对应的任务节点模型
        $nodeModel = $processModel->getNode($execution->getProcessTask()->getData('task_name'));

        // 8. 调用节点模型执行方法
        $nodeModel->execute($execution);

        return $execution->getProcessTaskList();
    }

    public function executeAndJumpTask(string $processTaskId, string $operator, Dict $args, string $nodeName=''): array
    {
        $execution = $this->execute($processTaskId, $operator, $args);
        if ($execution == null) {
            return [];
        }
        $model = $execution->getProcessModel();
        if (empty($nodeName)) {
            $newTask = $this->processTaskService()->rejectTask($model, $execution->getProcessTask());
            $execution->addTask($newTask);
        } else {
            $nodeModel = $model->getNode($nodeName);
            if ($nodeModel == null) {
                throw new LFlowException([99999999, "根据节点名称[" . $nodeName . "]无法找到节点模型"]);
            }
            // 判断是否为第一个任务节点
            if ($nodeModel instanceof TaskModel) {
                $taskModel = $nodeModel;
                if (ProcessFlowUtils::isFistTaskName($model, $taskModel->getName())) {
                    // 第一个任务节点为申请节点，处理人等于流程发起人
                    $taskModel->setAssignee($execution->getProcessInstance()->getData('operator'));
                }
            }
            //动态创建转移对象，由转移对象执行execution实例
            $tm = new TransitionModel();
            $tm->setTarget($nodeModel);
            $tm->setEnabled(true);
            $tm->execute($execution);
        }
        return $execution->getProcessTaskList();
    }

    public function executeAndJumpToEnd(string $processTaskId, string $operator, Dict $args): array
    {
        $execution = $this->execute($processTaskId, $operator, $args);
        if ($execution == null) {
            return [];
        }
        $model        = $execution->getProcessModel();
        $endModelList = $model->getModels(EndModel::class);
        foreach ($endModelList as $endModel) {
            $tm = new TransitionModel();
            $tm->setTarget($endModel);
            $tm->setEnabled(true);
            $tm->execute($execution);
        }
        // 将流程状态修改为已拒绝
        $processInstance = new ProcessInstance();
        $processInstance->set('id', $execution->getProcessInstanceId());
        $processInstance->set('state', ProcessInstanceStateEnum::REJECT[0]);
        $processInstance->set('update_time', time());
        $processInstance->set('update_user', $operator);
        return $execution->getProcessTaskList();
    }

    public function executeAndJumpToFirstTaskNode(string $processTaskId, string $operator, Dict $args): array
    {
        $execution = $this->execute($processTaskId, $operator, $args);
        if ($execution == null) {
            return [];
        }
        $model      = $execution->getProcessModel();
        $startModel = $model->getStart();
        foreach ($startModel->getOutputs() as $transitionModel) {
            $transitionModel->setEnabled(true);
            // 调整参与者为流程发起人
            if ($transitionModel->getTarget() instanceof TaskModel) {
                $taskModel = $transitionModel->getTarget();
                $taskModel->setAssignee($execution->getProcessInstance()->getData('operator'));
            }
            $transitionModel->execute($execution);
        }
        return $execution->getProcessTaskList();
    }

    private function execute(string $processTaskId, string $operator, Dict $args): ?Execution
    {
        // 1.1 根据id查询正在进行中的流程任务
        $processTask = $this->processTaskService()->getById($processTaskId);
        if ($processTask == null || !StringHelper::equalsIgnoreCase(ProcessTaskStateEnum::DOING[0], $processTask->getData('task_state'))) {
            throw new LFlowException(LfErrEnum::NOT_FOUND_DOING_PROCESS_TASK);
        }
        // 1.2 判断是否可以执行任务
        if (!$this->processTaskService()->isAllowed($processTask, $operator)) {
            // 当前参与者不能执行该流程任务
            throw new LFlowException(LfErrEnum::NOT_ALLOWED_EXECUTE);
        }
        // 2. 根据流程任务查询流程实例
        $processInstance = $this->processInstanceService()->getById($processTask->getData('process_instance_id'));
        // 3. 根据流程实例查询流程定义
        $processDefine = $this->processDefineService()->getById($processInstance->getData('process_define_id'));
        // 4. 将流程定义文件转成流程模型
        $processModel = ModelParser::parse($processDefine->getData('content'));
        // 5. 将流程任务状态修改为已完成
        $this->processTaskService()->finishProcessTask($processTaskId, $operator, $args);
        $processTask->set('task_state', ProcessTaskStateEnum::FINISHED[0]);
        // 6. 根据流程定义、实例、任务构建执行参数对象
        $execution = new Execution();
        $execution->setProcessModel($processModel);
        $execution->setProcessInstance($processInstance);
        $execution->setProcessInstanceId($processInstance->getData('id'));
        $execution->setProcessTask($processTask);
        $execution->setProcessTaskId($processTaskId);
        $execution->setOperator($operator);
        $execution->setEngine($this);

        $processInstanceVariable = $processInstance->getData('variable');
        $newArgs                 = ProcessFlowUtils::variableToDict($processInstanceVariable);
        $newArgs->putAll($args->getAll());
        $execution->setArgs($newArgs);
        // 如果提交参数中存在f_前辍参数，则更新到流程实例变量中
        $prefixArgs = array_filter($args->toArray(), function ($value, $key) {
            if (str_starts_with($key, ProcessConst::FORM_DATA_PREFIX)) {
                return true; // 返回 false 以便从过滤结果中排除这些键值对
            }
            return false; // 其他键值对会被包含在过滤结果中（但在这个上下文中我们不关心它们）
        }, ARRAY_FILTER_USE_BOTH); // 使用 ARRAY_FILTER_USE_BOTH 同时传递键和值给回调函数

        $addsArgs   = ProcessFlowUtils::variableToDict($prefixArgs);
        if (!empty($addsArgs->getAll())) {
            $this->processInstanceService()->addVariable($processInstance->getData('id'), $addsArgs);
        }
        return $execution;
    }
}
