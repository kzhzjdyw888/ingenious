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

namespace ingenious\service\interface;


use ingenious\db\ProcessDefine;

interface ProcessDefineServiceInterface
{

    /**
     * 添加流程定义
     *
     * @param object $param
     *
     * @return bool
     */
    public function create(object $param): bool;

    /**
     * 更新流程定义
     *
     * @param object $param
     *
     * @return bool
     */
    public function update(object $param): bool;

    /**
     * 自定义分页查询流程定义
     *
     * @param object $param
     *
     * @return array
     */
    public function page(object $param): array;

    /**
     * 通过id查询
     *
     * @param string $id
     *
     * @return \ingenious\db\ProcessDefine|null
     */
    public function findById(string $id): ?ProcessDefine;

    /**
     * 部署流程定义文件，同name存在多个版本
     *
     * @param object $param
     * @param string $operation
     *
     * @return string
     */
    public function deploy(object $param,string $operation): string;

    /**
     * 重新部署定义文件，按id更新json
     *
     * @param string     $processDefineId
     * @param object     $inputStream
     * @param string|int $operation
     *
     * @return void
     */
    public function redeploy(string $processDefineId, object $inputStream,string|int $operation): void;

    /**
     * 卸载流程
     *
     * @param string     $processDefineId
     * @param string|int $operation
     */
    public function unDeploy(string $processDefineId,string|int $operation): void;

    /**
     * 更新type
     *
     * @param string     $processDefineId
     * @param string     $type
     * @param string|int $operation
     *
     * @return void
     */
    public function updateType(string $processDefineId, string $type,string|int $operation): void;

    /**
     * 根据流程ID获取流程定义对象
     * @param string $processDefineId
     *
     * @return \ingenious\db\ProcessDefine|null
     */
    public function getById(string $processDefineId): ?ProcessDefine;

    /**
     * 根据id获取流程模型
     *
     * @param string $processDefineId
     *
     * @return mixed
     */
    public function getProcessModel(string $processDefineId): mixed;

    /**
     * 流程定义转流程模型
     *
     * @param \ingenious\db\ProcessDefine $processDefine
     *
     * @return mixed
     */
    public function processDefineToModel(ProcessDefine $processDefine): mixed;

    /**
     * 根据id获取定义json字符串
     *
     * @param string $processDefineId
     *
     * @return mixed
     */
    public function getDefineJsonStr(string $processDefineId): mixed;

    /**
     * 获取流程定义json对象
     *
     * @param string $processDefineId
     *
     * @return mixed
     */
    public function getDefineJsonObject(string $processDefineId): mixed;

    /**
     * 启用/禁用
     *
     * @param object $param
     *
     * @return void
     */
    public function upAndDown(object $param): void;

    /**
     * 根据名称获取最新的流程定义
     *
     * @param string $name
     *
     * @return \ingenious\db\ProcessDefine|null
     */
    public function getLastByName(string $name): ?ProcessDefine;

    /**
     * 通过流程定义名称和版本获取流程定义
     *
     * @param string $name
     * @param int    $version
     *
     * @return \ingenious\db\ProcessDefine|null
     */
    public function getProcessDefineByVersion(string $name, int $version): ?ProcessDefine;

}
