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

/**
 * 流程定义
 *
 * @author Mr.April
 * @since  1.0
 */
class ProcessType extends BaseModel
{
    use ModelTrait;
    use UuidAutoModelTrait;

    /**
     * 表名
     *
     * @var string
     */
    protected $name = 'wf_process_type';

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

    public function searchPidAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('pid', $value);
        }
    }

    /**
     * 类型名称
     *
     * @param $query
     * @param $value
     */
    public function searchNameAttr($query, $value)
    {

        if (!empty($value)) {
            $query->whereLike('name', '%' . $value . '%');
        }
    }

    /**
     * 是否删除搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchIsDelAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('is_del', $value);
        } else {
            $query->where('is_del', 0);
        }
    }

    /**
     * 状态搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchStatusAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('status', $value);
        }
    }

    public function parent(): \think\model\relation\HasOne
    {
        return $this->hasOne(ProcessType::class, 'id', 'pid');
    }

}
