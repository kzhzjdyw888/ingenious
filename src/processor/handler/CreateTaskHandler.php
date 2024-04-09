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

namespace ingenious\processor\handler;

use ingenious\core\Execution;
use ingenious\core\ServiceContext;
use ingenious\enums\ProcessEventTypeEnum;
use ingenious\enums\ProcessTaskPerformTypeEnum;
use ingenious\event\ProcessEventService;
use ingenious\interface\ProcessNodeInterceptor;
use ingenious\lib\util\StringHelper;
use ingenious\model\TaskModel;
use ingenious\processor\IHandler;

/**
 * 创建任务处理器
 *
 * @author Mr.April
 * @since  1.0
 */
class CreateTaskHandler implements IHandler
{

    private TaskModel $taskModel;

    public function __construct(TaskModel $taskModel)
    {
        $this->taskModel = $taskModel;
    }

    /**
     * handle
     *
     * @param \ingenious\core\Execution $execution
     */
    public function handle(Execution $execution): void
    {
        $processTaskList = [];
        // 根据任务类型创建任务
        if (strcasecmp((string)ProcessTaskPerformTypeEnum::COUNTERSIGN[0], (string)$this->taskModel->getPerformType()[0] ?? '') === 0) {
            $processTaskList = $execution->getEngine()->processTaskService()->createCountersignTask($this->taskModel, $execution);
            //会签任务
        } else {
            //创建普通任务
            $processTaskList = $execution->getEngine()->processTaskService()->createTask($this->taskModel, $execution);
        }
        // 将任务添加到执行对象中
        $execution->addTasks($processTaskList);

        // 从服务容器中获取拦截器并执行
        $interceptors = ServiceContext::findAll(ProcessNodeInterceptor::class);
        foreach ($interceptors as $interceptor) {
            $interceptor->intercept($execution);
        }

        // 发布流程任务开始事件
        foreach ($processTaskList as $processTask) {
            ProcessEventService::publishNotification(ProcessEventTypeEnum::PROCESS_TASK_START[0], $processTask ->getData('id'));
        }
    }
}
