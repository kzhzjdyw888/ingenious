<?php

namespace app\model\system\setting;

use phoenix\basic\BaseModel;
use phoenix\traits\ModelTrait;

class SystemUserPost extends BaseModel
{

    use ModelTrait;

    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'user_post';

    // 定义与Post模型的多对一关联关系0808
    public function post(): \think\model\relation\BelongsTo
    {
        return $this->belongsTo(SystemPost::class, 'post_id');
    }
}
