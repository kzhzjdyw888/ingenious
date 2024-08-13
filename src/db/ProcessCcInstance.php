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

namespace ingenious\db;

use ingenious\libs\base\BaseModel;
use ingenious\libs\traits\ModelTrait;
use ingenious\libs\traits\UuidAutoModelTrait;

/**
 * 流程实例抄送-模型
 *
 * @author Mr.April
 * @since  1.0
 */
class ProcessCcInstance extends BaseModel
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
     * 表名
     *
     * @var string
     */
    protected $name = 'wf_process_cc_instance';

    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    protected $type = [
        'create_time' => 'timestamp:Y-m-d H:i:s',
        'update_time' => 'timestamp:Y-m-d H:i:s',
    ];

    /**
     * ProcessInstanceId 搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchProcessInstanceIdAttr($query, $value): void
    {
        if (!empty($value)) {
            $query->where('process_instance_id', $value);
        }
    }

    /**
     * Id 搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchIdAttr($query, $value): void
    {
        if (!empty($value)) {
            $query->where('id', $value);
        }
    }

    /**
     * State  状态搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchStateAttr($query, $value): void
    {
        if (!empty($value)) {
            $query->where('state', $value);
        }
    }

    /**
     * ActorId  被抄送人ID 搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchActorIdAttr($query, $value): void
    {
        if (!empty($value)) {
            $query->where('actor_id', $value);
        }
    }

    /**
     * 流程实例一对一关联
     * @return \think\model\relation\HasOne
     */
    public function processInstance(): \think\model\relation\HasOne
    {
        return $this->hasOne(ProcessInstance::class, 'id', 'process_instance_id');
    }

}
