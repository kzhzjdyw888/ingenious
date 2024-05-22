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

namespace app\model\system\admin;

use phoenix\basic\BaseModel;
use phoenix\traits\JwtAuthModelTrait;
use phoenix\traits\ModelTrait;
use phoenix\utils\Str;
use think\db\Query;
use think\Model;

/**
 * 管理员模型
 * Class SystemAdmin
 *
 * @package app\model\system\admin
 */
class SystemAdmin extends BaseModel
{
    use ModelTrait;
    use JwtAuthModelTrait;

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
    protected $name = 'system_admin';

    protected array $insert = ['create_time', 'update_time'];

    protected $type = [
        'create_time' => 'timestamp:Y-m-d H:i:s',
        'update_time' => 'timestamp:Y-m-d H:i:s',
        'last_time'   => 'timestamp:Y-m-d H:i:s',
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

    /**
     * inID搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchIdInAttr(Query $query, $value)
    {
        if (!empty($value)) {
            $query->whereIn('id', $value);
        }
    }

    /**
     * 管理员级别搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchLevelAttr(Query $query, $value)
    {
        if (is_array($value)) {
            $query->where('level', $value[0], $value[1]);
        } else {
            $query->where('level', $value);
        }
    }

    /**
     * 管理员账号和姓名搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchAccountLikeAttr(Query $query, $value)
    {
        if ($value) {
            $query->whereLike('account|real_name', '%' . $value . '%');
        }
    }

    public function searchNotAccountAttr(Query $query, $value)
    {
        if ($value) {
            $query->where('account', '<>', $value);
        }
    }

    /**
     * 管理员账号搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchAccountAttr(Query $query, $value)
    {
        if ($value) {
            $query->where('account', $value);
        }
    }

    /**
     * 管理员账号搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchInAccountAttr(Query $query, $value)
    {
        if ($value) {
            $query->whereIn('account', $value);
        }
    }

    /**
     * 是否删除搜索器
     *
     * @param \think\db\Query $query
     */
    public function searchIsDelAttr(Query $query)
    {
        $query->where('delete_time', null);
    }

    /**
     * 状态搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchStatusAttr(Query $query, $value)
    {
        if ($value != '' && $value != null) {
            $query->where('status', $value);
        }
    }

    /**
     * 定义多对多远程关联部门模型
     *
     * @return \think\model\relation\HasManyThrough
     */
    public function departments(): \think\model\relation\HasManyThrough
    {
        //目标模型 中间模型  关联中间模型字段  关联最终模型字段 当前模型关联中间模型字段 最终模型关联字段
        return $this->hasManyThrough(SystemDept::class, SystemAdminDept::class, 'admin_id', 'dept_id', 'id', 'dept_id');
    }

    /**
     * 定义多对多远程关联职位
     */
    public function positions(): \think\model\relation\HasManyThrough
    {
        return $this->hasManyThrough(SystemPost::class, SystemAdminPost::class, 'admin_id', 'post_id', 'id', 'post_id');
    }

}
