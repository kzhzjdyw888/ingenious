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

namespace madong\ingenious\interface\services;

use madong\ingenious\interface\model\IProcessDefine;
use madong\ingenious\model\ProcessModel;

interface IProcessDefineService
{
    /**
     * 创建一个新的流程定义
     *
     * @param object $param 参数对象
     * @return IProcessDefine|null 返回创建的流程定义对象或 null
     */
    public function created(object $param): ?IProcessDefine;

    /**
     * 更新指定的流程定义
     *
     * @param object $param 参数对象，必须包含 id
     * @return bool 返回更新是否成功
     */
    public function updated(object $param): bool;

    /**
     * 删除指定的流程定义
     *
     * @param string|int|array $data 要删除的 ID，可以是单个 ID、数组或逗号分隔的字符串
     * @return array 返回已删除的 ID 列表
     */
    public function del(string|int|array $data): array;

    /**
     * 获取流程定义列表
     *
     * @param object $param 过滤参数
     * @return array 返回包含 items 和 total 的数组
     */
    public function list(object $param): array;

    /**
     * 根据 ID 查找流程定义
     *
     * @param string $id 要查找的 ID
     * @return IProcessDefine|null 返回找到的流程定义对象或 null
     */
    public function findById(string $id): ?IProcessDefine;

    /**
     * 部署流程定义
     *
     * @param object $param 部署参数
     * @param string $operation 操作用户
     * @return bool 返回部署是否成功
     */
    public function deploy(object $param, string $operation): bool;

    /**
     * 重新部署流程定义
     *
     * @param string|int $processDefineId 流程定义 ID
     * @param object $inputStream 输入流对象
     * @param string|int $operation 操作用户
     * @return bool 返回重新部署是否成功
     */
    public function redeploy(string|int $processDefineId, object $inputStream, string|int $operation): bool;

    /**
     * 启用流程定义
     *
     * @param string|int|array $data 要启用的 ID
     * @param string|int $operation 操作用户
     * @return array 返回已启用的 ID 列表
     */
    public function enable(string|int|array $data, string|int $operation): array;

    /**
     * 禁用流程定义
     *
     * @param string|int|array $data 要禁用的 ID
     * @param string|int $operation 操作用户
     * @return array 返回已禁用的 ID 列表
     */
    public function disable(string|int|array $data, string|int $operation): array;

    /**
     * 更新流程定义状态
     *
     * @param string $processDefineId 流程定义 ID
     * @param string|int $state 新状态
     * @param string|int $operation 操作用户
     */
    public function updateState(string $processDefineId, string|int $state, string|int $operation): void;

    /**
     * 获取流程模型
     *
     * @param string $processDefineId 流程定义 ID
     * @return ProcessModel|null 返回流程模型或 null
     */
    public function getProcessModel(string $processDefineId): ?ProcessModel;

    /**
     * 将流程定义转换为模型
     *
     * @param IProcessDefine $processDefine 流程定义对象
     * @return ProcessModel|null 返回流程模型或 null
     */
    public function processDefineToModel(IProcessDefine $processDefine): ?ProcessModel;

    /**
     * 获取流程定义的 JSON 字符串
     *
     * @param string $processDefineId 流程定义 ID
     * @return string|null 返回 JSON 字符串或 null
     */
    public function getDefineJsonStr(string $processDefineId): ?string;

    /**
     * 获取流程定义的 JSON 对象
     *
     * @param string $processDefineId 流程定义 ID
     * @return \stdClass|string|bool 返回 JSON 对象或其他类型
     */
    public function getDefineJsonObject(string $processDefineId): \stdClass|string|bool;

    /**
     * 根据名称获取最新的流程定义
     *
     * @param string $name 流程定义名称
     * @return IProcessDefine|null 返回最新的流程定义对象或 null
     */
    public function getLastByName(string $name): ?IProcessDefine;

    /**
     * 根据名称和版本获取流程定义
     *
     * @param string $name 流程定义名称
     * @param int|float $version 版本号
     * @return IProcessDefine|null 返回指定版本的流程定义对象或 null
     */
    public function getProcessDefineByVersion(string $name, int|float $version): ?IProcessDefine;
}

