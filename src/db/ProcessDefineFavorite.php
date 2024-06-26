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
 * 流程定义
 *
 * @author Mr.April
 * @since  1.0
 */
class ProcessDefineFavorite extends BaseModel
{
    use ModelTrait;
    use UuidAutoModelTrait;

    /**
     * 表名
     *
     * @var string
     */
    protected $name = 'wf_process_define_favorite';

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

    /**
     * 用户ID搜索器
     *
     * @param $query
     * @param $value
     */
    public function searchUserIdAttr($query, $value)
    {
        if ($value) {
            $query->where('user_id', $value);
        }
    }

    /**
     * 流程定义ID搜索器
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
     * 定义管理一对一流程定义表
     *
     * @return \think\model\relation\HasOne
     */
    public function processDefine(): \think\model\relation\HasOne
    {
        return $this->hasOne(ProcessDefine::class, 'id', 'process_define_id')->bind(['display_name','version']);
    }

}
