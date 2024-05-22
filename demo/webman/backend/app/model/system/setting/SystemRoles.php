<?php

namespace app\model\system\setting;

use app\common\traits\JwtAuthModelTrait;
use app\common\traits\ModelTrait;
use app\model\BaseModel;

/**
 * 角色
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemRoles extends BaseModel
{
    use ModelTrait;

    protected $connection = 'rbac';

    /**
     * 数据表主键
     *
     * @var string
     */
    protected $pk = 'role_id';

    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'roles';

    //自动时间戳
    protected $autoWriteTimestamp = true;

    //自动时间戳格式
    protected $dateFormat = 'Y-m-d H:i:s';

    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    // 设置字段信息
    protected $schema = [
        'role_id'     => 'string',
        'role_name'   => 'string',
        'role_code'   => 'int',
        'parent_id'   => 'string',
        'status'      => 'int',
        'weight'      => 'int',
        'icon'        => 'string',
        'is_super'    => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'remarks'     => 'string',
    ];

    // 设置字段自动转换类型
    protected $type = [];

    // 设置废弃字段
    protected $disuse = [];

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

}
