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

class ProcessDesignHistory extends BaseModel
{
    use ModelTrait;
    use UuidAutoModelTrait;

    /**
     * 表名
     *
     * @var string
     */
    protected $name = 'wf_process_design_history';

    /**
     * 主键
     *
     * @var string
     */
    protected $pk = 'id';

    protected $autoWriteTimestamp = true;

    protected $type = [
        'create_time' => 'timestamp:Y-m-d H:i:s',
    ];

    // 设置json类型字段
    protected $json = ['content'];

    /**
     * ID  搜索器
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
     * 流程设计ID搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchProcessDesignIdAttr($query, $value)
    {
        if ($value) {
            $query->where('process_design_id', $value);
        }
    }
}
