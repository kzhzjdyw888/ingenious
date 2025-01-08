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

namespace madong\ingenious\domain;


use madong\ingenious\interface\IExecution;
use madong\ingenious\interface\IProcessInterceptor;

/**
 * 任务拦截器-处理代理人
 *
 * @author Mr.April
 * @since  1.0
 */
class SurrogateInterceptorI implements IProcessInterceptor
{
    public function intercept(IExecution $execution): void
    {
        $processTaskList = $execution->getProcessTaskList();
        foreach ($processTaskList as $processTask) {
            $actorList = $execution->getEngine()->processTaskService()->getTaskActors($processTask->getData('id'));
            foreach ($actorList as $actor) {
                $agent = $execution->getEngine()->processTaskService()->getSurrogate($actor, $execution->getProcessModel()->getName());
                if (!empty($agent)) {
                    $execution->getEngine()->processTaskService()->addTaskActor($processTask->getData('id'), $agent);
                }
            }
        }
    }
}
