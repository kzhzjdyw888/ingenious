<?php

namespace app\model\system\crontab;

use app\common\traits\JwtAuthModelTrait;
use app\common\traits\ModelTrait;
use app\model\BaseModel;
use think\model\concern\SoftDelete;

class SystemCrontab extends BaseModel
{
    use ModelTrait;
    use SoftDelete;

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
    protected $name = 'system_timer';
    protected string $deleteTime = 'delete_time';

    protected array $insert = ['create_time', 'update_time'];

    protected $type = [
        'create_time' => 'timestamp:Y-m-d H:i:s',
        'update_time' => 'timestamp:Y-m-d H:i:s',
        'delete_time' => 'timestamp:Y-m-d H:i:s',
    ];

    /**
     * 新增自动创建字符串id
     *
     * @param $model
     *
     * @return void
     */
    protected static function onBeforeInsert($model): void
    {
        $uuid                = !empty($model->{$model->pk}) ? $model->{$model->pk} : Str::uuid();
        $model->{$model->pk} = $uuid;
    }

}
