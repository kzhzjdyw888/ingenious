<?php
/**
 *+------------------
 * Ingenious
 *+------------------
 * Copyright (c) https://gitee.com/ingenstream/ingenious  All rights reserved. 本版权不可删除，侵权必究
 *+------------------
 * Author: Mr. April (405784684@qq.com)
 *+------------------
 * Software Registration Number: 2024SR0694589
 * Official Website: http://www.ingenstream.cn
 */

namespace ingenious\interface;

use ingenious\db\ProcessInstance;
use ingenious\libs\utils\Dict;
use ingenious\service\ProcessDefineService;
use ingenious\service\ProcessInstanceService;
use ingenious\service\ProcessTaskService;

/**
 * @author Mr.April
 * @since  3.0
 */
interface ProcessEnginesInterface
{

    /**
     * 获取流程定义服务
     *
     * @return ProcessDefineService
     */
    public function processDefineService(): ProcessDefineService;

    /**
     * 获取流程实例服务
     *
     * @return ProcessInstanceService
     */
    public function processInstanceService(): ProcessInstanceService;

    /**
     * 获取流程任务服务
     *
     * @return ProcessTaskService
     */
    public function processTaskService(): ProcessTaskService;

    /**
     * 根据流程定义ID、操作人ID、启动流程参数启动流程实例
     *
     * @param string                 $id
     * @param string                 $operator
     * @param \ingenious\libs\utils\Dict $args
     * @param string|null            $parentId
     * @param string|null            $parentNodeName
     *
     * @return ProcessInstance 流程实例
     */
    public function startProcessInstanceById(string $id, string $operator, Dict $args, string|null $parentId = null, string|null $parentNodeName = null): ProcessInstance;

    /**
     * 执行流程任务
     *
     * @param string                 $processTaskId
     * @param string                 $operator
     * @param \ingenious\libs\utils\Dict $args
     *
     * @return array
     */
    public function executeProcessTask(string $processTaskId, string $operator, Dict $args): array;

    /**
     * 执行流程任务并跳转
     *
     * @param string                 $processTaskId
     * @param string                 $operator
     * @param \ingenious\libs\utils\Dict $args
     * @param string                 $nodeName
     *
     * @return array
     */
    public function executeAndJumpTask(string $processTaskId, string $operator, Dict $args, string $nodeName): array;

    /**
     * 执行流程任务并跳转到结束节点
     *
     * @param string                 $processTaskId
     * @param string                 $operator
     * @param \ingenious\libs\utils\Dict $args
     *
     * @return array
     */
    public function executeAndJumpToEnd(string $processTaskId, string $operator, Dict $args): array;

    /**
     * 执行流程任务并跳转到第一个任务节点
     *
     * @param string                 $processTaskId
     * @param string                 $operator
     * @param \ingenious\libs\utils\Dict $args
     *
     * @return array
     */
    public function executeAndJumpToFirstTaskNode(string $processTaskId, string $operator, Dict $args): array;
}
