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

namespace ingenious\service\interface;



use ingenious\core\Execution;
use ingenious\db\ProcessDefine;
use ingenious\db\ProcessInstance;
use ingenious\libs\utils\Dict;
use ingenious\model\TaskModel;
use ingenious\vo\ProcessInstanceVO;

/**
 * 流程实例服务类
 *
 * @author Mr.April
 * @since  1.0
 */
interface ProcessInstanceServiceInterface
{
    /**
     * 添加流程设计
     *
     * @param object $param
     *
     * @return bool
     */
    public function create(object $param): bool;

    /**
     * 更新流程实例
     *
     * @param object $param
     *
     * @return bool
     */
    public function update(object $param): bool;

    /**
     * 自定义分页查询流程实例
     *
     * @param object $param
     *
     * @return array
     */
    public function page(object $param): array;

    /**
     * 将流程实例修改为已完成
     *
     * @param string $id
     *
     */
    public function findById(string $id): ?ProcessInstance;

    /**
     * 将流程实例修改为已完成
     *
     * @param string $processInstanceId
     */
    public function finishProcessInstance(string $processInstanceId): void;

    /**
     * 根据流程、操作人员、父流程实例ID创建流程实例
     *
     * @param \ingenious\db\ProcessDefine $processDefine 流程定义对象
     * @param String                  $operator      操作人员ID
     * @param \ingenious\libs\utils\Dict  $args          参数列表
     * @param string                  $parentId
     * @param string                  $parentNodeName
     *
     * @return \ingenious\db\ProcessInstance|null 活动流程实例对象
     */
    public function createProcessInstance(ProcessDefine $processDefine, string $operator, Dict $args, string $parentId, string $parentNodeName): ?ProcessInstance;

    /**
     * 向指定实例id添加全局变量数据
     *
     * @param string                 $processInstanceId
     * @param \ingenious\libs\utils\Dict $args 变量数据
     *
     * @return void
     */
    public function addVariable(string $processInstanceId, Dict $args): void;

    /**
     * 移除指定实例id中的全局变量
     *
     * @param string $processDefineId 实例ID
     * @param string $keys            移除变量keys
     */
    public function removeVariable(string $processDefineId, string $keys): void;

    /***
     * 保存流程实例
     *
     * @param \ingenious\db\ProcessInstance $processInstance 流程实例对象
     */
    public function saveProcessInstance(ProcessInstance $processInstance): ?ProcessInstance;

    /**
     * 流程实例强制终止与唤醒相对应
     *
     * @param string $processInstanceId 流程实例id
     * @param string $operator          处理人
     */
    public function interrupt(string $processInstanceId, string $operator): void;

    /**
     * 唤醒历史流程实例与终止相对应
     *
     * @param string $processInstanceId 流程实例id
     * @param string $operator          处理人员
     */
    public function resume(string $processInstanceId, string $operator): void;

    /**
     * 挂起流程与激活相对应
     *
     * @param string $processInstanceId 流程实例id
     * @param string $operator          处理人员
     */
    public function pending(string $processInstanceId, string $operator): void;

    /**
     * 激活流程与挂起相对应
     *
     * @param string $processInstanceId
     * @param string $operator
     */
    public function activate(string $processInstanceId, string $operator): void;

    /**
     * 更新流程实例
     *
     * @param \ingenious\db\ProcessInstance $processInstance 流程实例对象
     */
    public function updateProcessInstance(ProcessInstance $processInstance): void;

    /**
     * 根据ID获取流程实例
     *
     * @param string $id
     *
     * @return \ingenious\db\ProcessInstance|null
     */
    public function getById(string $id): ?ProcessInstance;

    /**
     * 启动且执行流程（自动执行第一个节点）
     *
     * @param string                 $processDefineId
     * @param \ingenious\libs\utils\Dict $args
     */
    public function startAndExecute(string $processDefineId, Dict $args): void;

    /**
     * 获取流程实例高亮数据
     *
     * @param string $processInstanceId
     *
     * @return array
     */
    public function highLight(string $processInstanceId): array;

    /**
     * 审批记录
     *
     * @param string $processInstanceId
     *
     * @return array|null
     */
    public function approvalRecord(string $processInstanceId): ?array;

    /**
     * 撤回
     *
     * @param string $processInstanceId
     * @param string $operator
     */
    public function withdraw(string $processInstanceId, string $operator): void;

    /**
     * 更新会签变量
     *
     * @param \ingenious\model\TaskModel $taskModel
     * @param \ingenious\core\Execution  $execution
     * @param array                  $taskActors
     */
    public function updateCountersignVariable(TaskModel $taskModel, Execution $execution, array $taskActors): void;

    /**
     * 创建实例抄送
     *
     * @param string $processInstanceId
     * @param string $creator
     * @param string $actorIds
     */
    public function createCCInstance(string $processInstanceId, string $creator, string $actorIds): void;

    /**
     * 更新抄送状态
     *
     * @param string $processInstanceId
     * @param string $actorId
     */
    public function updateCCStatus(string $processInstanceId, string $actorId): void;

    /**
     * 自定义分页查询我的抄送
     *
     * @param object $param
     *
     * @return array
     */
    public function ccInstancePage(object $param): array;

}
