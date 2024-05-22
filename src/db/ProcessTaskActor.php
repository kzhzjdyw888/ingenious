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

namespace ingenious\db;

use ingenious\libs\base\BaseModel;
use ingenious\libs\traits\ModelTrait;
use ingenious\libs\traits\UuidAutoModelTrait;

class ProcessTaskActor extends BaseModel
{
    use ModelTrait;
    use UuidAutoModelTrait;

    /**
     * 数据表主键
     *
     * @var string
     */
    protected $pk = 'id';

    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'wf_process_task_actor';

    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'finish_time';

    protected $type = [
        'create_time' => 'timestamp:Y-m-d H:i:s',
        'finish_time' => 'timestamp:Y-m-d H:i:s',
    ];

    // 设置字段信息
    protected $schema = [
        'id'              => 'string',
        'process_task_id' => 'string',
        'actor_id'        => 'string|int',
        'create_time'     => 'int',
        'create_user'     => 'string',
    ];

    /**
     * ID-搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchIdAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('id', $value);
        }
    }

    /**
     * 流程任务ID-搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchProcessTaskIdAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('process_task_id', $value);
        }
    }

    /**
     * 参与者ID-搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchActorIdAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('actor_id', $value);
        }
    }

    /**
     * 流程任务用户关联任务task
     *
     * @return \think\model\relation\HasOne
     */
    public function processTask(): \think\model\relation\HasOne
    {
        return $this->hasOne(ProcessTask::class, 'id', 'process_task_id')->bind(['task_name','display_name','task_state']);
    }
}
