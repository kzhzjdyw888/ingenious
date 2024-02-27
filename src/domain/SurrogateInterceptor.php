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

namespace ingenious\domain;

use ingenious\core\Execution;
use ingenious\interface\ProcessInterceptor;

/**
 * 任务拦截器-处理代理人
 *
 * @author Mr.April
 * @since  1.0
 */
class SurrogateInterceptor implements ProcessInterceptor
{
    public function intercept(Execution $execution): void
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
