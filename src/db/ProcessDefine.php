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

/**
 * @method save()
 */
class ProcessDefine extends BaseModel
{
    use ModelTrait;
    use UuidAutoModelTrait;

    /**
     * 表名
     *
     * @var string
     */
    protected $name = 'wf_process_define';

    /**
     * 主键
     *
     * @var string
     */
    protected $pk = 'id';

    // 设置json类型字段
    protected $json = ['content'];

    protected $autoWriteTimestamp = true;

    protected $type = [
        'create_time' => 'timestamp:Y-m-d H:i:s',
        'update_time' => 'timestamp:Y-m-d H:i:s',
    ];

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
     *  显示名称搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchDisplayNameAttr($query, $value)
    {
        if (!empty($value)) {
            $query->whereLike('display_name', '%' . $value . '%');
        }
    }

    /**
     * 状态搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchStateAttr($query, $value)
    {
        if ($value !== '') {
            $query->where('state', $value);
        }
    }

    /**
     *  描述搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchDescriptionAttr($query, $value)
    {
        if ($value) {
            $query->whereLike('description', '%' . $value . '%');
        }
    }

    /**
     * 分类搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchTypeIdAttr($query, $value)
    {
        if ($value) {
            $query->where('type_id', $value);
        }
    }

    /**
     * 流程版本-搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchVersionAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('version', $value);
        }
    }

    /**
     * 定义管理一对一流程类型表
     *
     * @return \think\model\relation\HasOne
     */
//    public function processType(): \think\model\relation\HasOne
//    {
//        return $this->hasOne(ProcessType::class, 'id', 'type_id');
//    }
//
//    public function processDefineFavorite(): \think\model\relation\HasOne
//    {
//        return $this->hasOne(ProcessDefineFavorite::class, 'process_define_id', 'id');
//    }

}
