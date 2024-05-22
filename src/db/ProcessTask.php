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

class ProcessTask extends BaseModel
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
    protected $name = 'wf_process_task';

    protected $autoWriteTimestamp = true;

    protected $type = [
        'create_time' => 'timestamp:Y-m-d H:i:s',
        'update_time' => 'timestamp:Y-m-d H:i:s',
        'finish_time' => 'timestamp:Y-m-d H:i:s',
    ];


    /**
     * JSON字段
     *
     * @var string[]
     */
    protected $json = ['variable'];

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
     * 流程实例ID-搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchProcessInstanceIdAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('process_instance_id', $value);
        }
    }

    /**
     * 任务名称自动识别in搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchTaskNameAttr($query, $value)
    {
        if (!empty($value)) {
            if (is_array($value)) {
                $query->whereIn('task_name', implode(',', $value));
            } else {
                $isTrue = count(explode(',', $value)) > 1;
                if ($isTrue) {
                    $query->whereIn('task_name', $value);
                } else {
                    $query->where('task_name', $value);
                }
            }
        }
    }

    public function searchDisplayNameAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('display_name', $value);
        }
    }

    /**
     * 任务类型-搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchTaskTypeAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('task_type', $value);
        }
    }

    /**
     * 参与类型-搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchPerformTypeAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('perform_type', $value);
        }
    }

    /**
     * 任务状态-搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchTaskStateAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('task_state', $value);
        }
    }

    /**
     * 任务状态自动识别notIn 搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchNotInTaskStateAttr($query, $value)
    {
        if (!empty($value)) {
            if (is_array($value)) {
                $query->whereNotIn('task_state', implode(',', $value));
            } else {
                $isTrue = count(explode(',', $value)) > 1;
                if ($isTrue) {
                    $query->whereNotIn('task_state', $value);
                } else {
                    $query->where('task_state', '<>', $value);
                }
            }
        }
    }

    /**
     * 任务处理人
     *
     * @param $query
     * @param $value
     */
    public function searchOperatorAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('operator', $value);
        }
    }

    /**
     * 父任务ID-搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchTaskParentIdAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('task_parent_id', $value);
        }
    }

    /**
     * 任务处理表单key-搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchFormKeyAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('form_key', $value);
        }
    }

    /**
     * 流程任务关联定义
     *
     * @return \think\model\relation\HasOne
     */
    public function processInstance(): \think\model\relation\HasOne
    {
        return $this->hasOne(ProcessInstance::class, 'id', 'process_instance_id');
    }

}
