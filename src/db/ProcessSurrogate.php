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

namespace ingenious\db;

use ingenious\libs\base\BaseModel;
use ingenious\libs\traits\ModelTrait;
use ingenious\libs\traits\UuidAutoModelTrait;

class ProcessSurrogate extends BaseModel
{
    use ModelTrait;
    use UuidAutoModelTrait;

    /**
     * 表名
     *
     * @var string
     */
    protected $name = 'wf_process_surrogate';

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
        'start_time'  => 'timestamp:Y-m-d H:i:s',
        'end_time'    => 'timestamp:Y-m-d H:i:s',
    ];

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
     * 流程名称-搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchProcessNameAttr($query, $value)
    {
        if ($value) {
            $query->where('process_name', $value);
        }
    }

    /**
     * 授权人-搜索器
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
     * 代理人-搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchSurrogateAttr($query, $value)
    {
        if ($value) {
            $query->where('surrogate', $value);
        }
    }

    /**
     * 启用状态-搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchEnabledAttr($query, $value)
    {
        if ($value !== '') {
            $query->where('enabled', $value);
        }
    }

    /**
     * 流程实例一对一流程定义表
     *
     * @return \think\model\relation\HasOne
     */
    public function processDefine(): \think\model\relation\HasOne
    {
        return $this->hasOne(ProcessDefine::class, 'name', 'process_name')->bind(['type_id', 'display_name', 'version']);
    }
}
