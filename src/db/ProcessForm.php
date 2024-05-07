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

class ProcessForm extends BaseModel
{
    use ModelTrait;
    use UuidAutoModelTrait;

    /**
     * 表名
     *
     * @var string
     */
    protected $name = 'wf_process_form';

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

    public function searchIdAttr($query, $value)
    {
        if ($value) {
            $query->where('id', $value);
        }
    }

    public function searchNotIdAttr($query, $value)
    {
        if ($value) {
            $query->where('id', '<>', $value);
        }
    }

    /**
     * 唯一编码
     *
     * @param $query
     * @param $value
     */
    public function searchNameAttr($query, $value)
    {
        if ($value) {
            $query->where('name', $value);
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
        $query->where('is_del', 0);
        if ($value) {
            $query->where('is_del', $value);
        }
    }

    /**
     * 显示名称搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchDisplayNameAttr($query, $value)
    {
        if ($value) {
            $query->whereLike('display_name', '%' . $value . '%');
        }
    }

    /**
     * 流程定义描述搜索器
     *
     * @param $query
     * @param $value +
     */
    public function searchDescriptionAttr($query, $value)
    {
        if ($value) {
            $query->whereLike('description', '%' . $value . '%');
        }
    }

    /**
     * 定义关联一对多历史表
     *
     * @return \think\model\relation\HasMany
     */
//    public function version(): \think\model\relation\HasMany
//    {
//        return $this->hasMany(ProcessDesignHis::class, 'process_design_id', 'id');
//    }

    /**
     * 定义管理一对一流程类型表
     *
     * @return \think\model\relation\HasOne
     */
    public function processType(): \think\model\relation\HasOne
    {
        return $this->hasOne(ProcessType::class, 'id', 'type_id');
    }

    /**
     * 定义关联一对多历史表
     *
     * @return \think\model\relation\HasMany
     */
    public function history(): \think\model\relation\HasMany
    {
        return $this->hasMany(ProcessDesignHis::class, 'process_design_id', 'id');
    }

    public function deleteWithHistory(): bool
    {
        // 删除关联的所有历史记录
        $this->history()->delete();
        // 删除设计本身
        return $this->delete();
    }
}
