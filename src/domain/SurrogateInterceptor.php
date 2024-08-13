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
