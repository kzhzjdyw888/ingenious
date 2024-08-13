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

use ingenious\db\ProcessInstance;
use ingenious\db\ProcessTask;
use ingenious\enums\ProcessTaskStateEnum;
use ingenious\libs\traits\DynamicMethodTrait;
use ingenious\libs\utils\Dict;
use ingenious\model\NodeModel;
use ingenious\model\ProcessModel;

/**
 * @method getProcessInstanceId()
 * @method getArgs()
 * @method getProcessTaskId()
 * @method setProcessTask(\lflow\db\ProcessTask $processTask)
 * @method setProcessModel(\lflow\model\ProcessModel|null $processModel)
 * @method setProcessInstance(\lflow\db\ProcessInstance $processInstance)
 * @method setProcessInstanceId(mixed $getData)
 * @method setEngine(\lflow\core\ProcessEngines $param)
 * @method setArgs(\lflow\libs\utils\Dict $args)
 * @method getEngine()
 * @method setNodeModel(\lflow\model\NodeModel $param)
 * @method getProcessModel()
 * @method getTaskModel()
 * @method getTasks()
 * @method setProcessTaskId($processTaskId)
 * @method setOperator($operator)
 * @method getProcessTask()
 * @method getProcessInstance()
 * @method getProcessTaskList()
 * @method getOperator()
 * @method isMerged()
 */
class Execution
{

    use DynamicMethodTrait;

    /**
     * @var string  流程实例ID
     */
    private string $processInstanceId;

    /**
     * @var string|int 当前流程任务ID
     */
    private string|int $processTaskId = 0;

    /**
     * @var \lflow\libs\utils\Dict|null  执行对象扩展参数
     */
    private ?Dict $args;

    /**
     * @var \lflow\model\ProcessModel|null  当前流程模型
     */
    private ?ProcessModel $processModel;

    /**
     * @var \lflow\db\ProcessTask|null 当前任务
     */
    private ?ProcessTask $processTask;

    /**
     * @var \lflow\db\ProcessInstance 当前流程实例
     */
    private ProcessInstance $processInstance;

    /**
     * @var array 所有任务集合
     */
    private array $processTaskList = [];

    /**
     * @var bool  是否可合并
     */
    private bool $isMerged;

    /**
     * @var \lflow\core\ProcessEngines 流程引擎对象
     */
    private ProcessEngines $engine;

    /**
     * @var string|null  操作人
     */
    private ?string $operator;

    /**
     * @var \lflow\model\NodeModel|null   当前节点模型
     */
    private ?NodeModel $nodeModel;

    public function __construct()
    {
    }

    /**
     * 添加任务到任务集合
     *
     * @param ProcessTask $processTask
     */
    public function addTask(ProcessTask $processTask): void
    {
        $this->processTaskList[] = $processTask;
    }

    /**
     * 添加任务集合
     *
     * @param array $processTasks
     */
    public function addTasks(array $processTasks): void
    {
        $this->processTaskList = array_merge($this->processTaskList, $processTasks);
    }

    /**
     * 获取正在进行中的任务列表
     *
     * @return array
     */
    public function getDoingTaskList(): array
    {
        return array_filter($this->processTaskList, function (ProcessTask $task) {
            return $task->getData('task_state') === ProcessTaskStateEnum::DOING[0];
        });
    }

    public function setMerged(bool $isMerged): void
    {
        $this->isMerged = $isMerged;
    }

    public function getMerged(): bool
    {
        return $this->isMerged ?? false;
    }


}
