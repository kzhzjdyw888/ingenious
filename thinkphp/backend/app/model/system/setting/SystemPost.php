<?php

namespace app\model\system\setting;

use phoenix\basic\BaseModel;
use phoenix\traits\ModelTrait;
use Ramsey\Uuid\Uuid;
use think\model\relation\HasMany;

class SystemPost extends BaseModel
{

    use ModelTrait;


    /**
     * 数据表主键
     *
     * @var string
     */
    protected $pk = 'post_id';

    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'post';

    /**
     * 新增前添加字符串id
     *
     * @param $model
     *
     * @return void
     */
    protected static function onBeforeInsert($model): void
    {
        $uuid                = !empty($model->{$model->pk}) ? $model->{$model->pk} : Uuid::uuid4()->toString();
        $model->{$model->pk} = $uuid;
    }

    /**
     * 定义与UserPost模型的一对多关联关系0808
     *
     * @return \think\model\relation\HasMany
     */
    public function userPosts(): HasMany
    {
        return $this->hasMany(SystemUserPost::class, 'post_id');
    }

}
