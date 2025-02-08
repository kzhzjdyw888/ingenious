<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

namespace app\model\system\dict;

use phoenix\basic\BaseModel;
use phoenix\traits\ModelTrait;
use phoenix\utils\Str;
use think\db\Query;
use think\Model;

/**
 * 字典选项模型
 * Class SystemLog
 *
 * @package app\model\system\log
 */
class SystemDict extends BaseModel
{
    use ModelTrait;


    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'system_options';
    /**
     * 数据表主键
     *
     * @var string
     */
    protected $pk = 'id';

    protected $insert = ['create_time'];

    protected $type = [
        'create_time' => 'timestamp:Y-m-d H:i:s',
        'update_time' => 'timestamp:Y-m-d H:i:s',
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

    protected function setCreateTimeAttr(): int
    {
        return time();
    }

    /**
     * name搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchNameAttr(Query $query, $value)
    {
        if ($value !== '') {
            $query->whereLike('name', $value . '%');
        }
    }

    /**
     * Id搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchIdAttr(Query $query, $value)
    {
        if ($value !== '') {
            if (in_array($value)) {
                $query->whereIn('id', $value);

            } else {
                $query->where('id', $value);
            }
        }
    }

    /**
     * ip搜索器
     *
     * @param Model $query
     * @param       $value
     */
    public function searchIpAttr($query, $value)
    {
        if ($value !== '') {
            $query->where('ip', 'LIKE', "%$value%");
        }
    }

    /**
     * 管理员id搜索器
     *
     * @param Model $query
     * @param       $value
     */
    public function searchAdminIdAttr($query, $value)
    {
        if (!empty($value)) {
            $query->whereIn('admin_id', $value);
        }
    }
}
