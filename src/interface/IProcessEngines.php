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

namespace madong\ingenious\interface;


use madong\interface\IDict;
use madong\ingenious\interface\model\IProcessInstance;
use madong\ingenious\interface\services\IProcessDefineService;
use madong\ingenious\interface\services\IProcessInstanceService;
use madong\ingenious\interface\services\IProcessTaskService;


/**
 * @author Mr.April
 * @since  3.0
 */
interface IProcessEngines
{

    /**
     * 获取流程定义服务
     *
     * @return \madong\ingenious\interface\services\IProcessDefineService|null
     */
    public function processDefineService(): ?IProcessDefineService;

    /**
     * 获取流程实例服务
     *
     * @return \madong\ingenious\interface\services\IProcessInstanceService|null
     */
    public function processInstanceService(): ?IProcessInstanceService;

    /**
     * 获取流程任务服务
     *
     * @return \madong\ingenious\interface\services\IProcessTaskService|null
     */
    public function processTaskService(): ?IProcessTaskService;

    /**
     * 根据流程定义ID、操作人ID、启动流程参数启动流程实例
     *
     * @param string                                   $id
     * @param string                                   $operator
     * @param \madong\ingenious\interface\IDict $args
     * @param string|null                              $parentId
     * @param string|null                              $parentNodeName
     *
     * @return \madong\ingenious\interface\model\IProcessInstance|null 流程实例
     */
    public function startProcessInstanceById(string $id, string $operator, IDict $args, string|null $parentId = null, string|null $parentNodeName = null): ?IProcessInstance;

    /**
     * 执行流程任务
     *
     * @param string                                   $processTaskId
     * @param string                                   $operator
     * @param \madong\ingenious\interface\IDict $args
     *
     * @return array
     */
    public function executeProcessTask(string $processTaskId, string $operator, IDict $args): array;

    /**
     * 执行流程任务并跳转
     *
     * @param string                                   $processTaskId
     * @param string                                   $operator
     * @param \madong\ingenious\interface\IDict $args
     * @param string                                   $nodeName
     *
     * @return array
     */
    public function executeAndJumpTask(string $processTaskId, string $operator, IDict $args, string $nodeName): array;

    /**
     * 执行流程任务并跳转到结束节点
     *
     * @param string                                   $processTaskId
     * @param string                                   $operator
     * @param \madong\ingenious\interface\IDict $args
     *
     * @return array
     */
    public function executeAndJumpToEnd(string $processTaskId, string $operator, IDict $args): array;

    /**
     * 执行流程任务并跳转到第一个任务节点
     *
     * @param string                                   $processTaskId
     * @param string                                   $operator
     * @param \madong\ingenious\interface\IDict $args
     *
     * @return array
     */
    public function executeAndJumpToFirstTaskNode(string $processTaskId, string $operator, IDict $args): array;
}
