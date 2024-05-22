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

class ProcessInstance extends BaseModel
{

    use ModelTrait;
    use UuidAutoModelTrait;

    /**
     * 表名
     *
     * @var string
     */
    protected $name = 'wf_process_instance';

    /**
     * 主键
     *
     * @var string
     */
    protected $pk = 'id';

    protected $autoWriteTimestamp = true;

    protected $type = [
        'create_time' => 'timestamp:Y-m-d H:i:s',
        'update_time' => 'timestamp:Y-m-d H:i:s',
    ];

    // 设置json类型字段
    protected $json = ['variable'];

    /**
     * ID-搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchIdAttr($query, $value)
    {
        if ($value) {
            $query->where('id', $value);
        }
    }

    /**
     * 父流程ID-搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchParentIdAttr($query, $value)
    {
        if ($value) {
            $query->where('parent_id', $value);
        }
    }

    /**
     * 流程定义ID-搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchProcessDefineIdAttr($query, $value)
    {
        if ($value) {
            $query->where('process_define_id', $value);
        }
    }

    /**
     *  实例状态搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchStateAttr($query, $value)
    {
        if ($value) {
            $query->where('state', $value);
        }
    }

    /**
     * 父流程依赖节点-搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchParentNodeNameAttr($query, $value)
    {
        if ($value) {
            $query->where('parent_node_name', $value);
        }
    }

    /**
     * 业务编号-搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchBusinessNoAttr($query, $value)
    {
        if ($value) {
            $query->where('business_no', $value);
        }
    }

    /**
     * 流程发起人-搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchOperatorAttr($query, $value)
    {
        if ($value) {
            $query->where('operator', $value);
        }
    }

    /**
     * 流程实例一对一流程定义表
     *
     * @return \think\model\relation\HasOne
     */
    public function processDefine(): \think\model\relation\HasOne
    {
        return $this->hasOne(ProcessDefine::class, 'id', 'process_define_id');
    }


    /**
     * 提取实例的表单数据字段属性
     *
     * @return \stdClass
     */
    public function getFormData(): \stdClass
    {
        $formData     = new \stdClass();
        $ext          = $this->getData('variable');
        $formDataKeys = array_filter(array_keys((array)$ext), function ($key) {
            return str_starts_with($key, 'f_');
        });
        foreach ($formDataKeys as $key) {
            $formData->{$key} = $ext->{$key};
        }
        return $formData;
    }
}

