<?php

namespace app\model\system\setting;

use app\common\traits\JwtAuthModelTrait;
use app\common\traits\ModelTrait;
use app\model\BaseModel;

class SystemUser extends BaseModel
{

    use ModelTrait;

    protected $connection = 'rbac';
    /**
     * 数据表主键
     *
     * @var string
     */
    protected $pk = 'user_id';

    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'users';

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
     * UserPost模型的一对多关联关系
     *
     * @return \think\model\relation\HasMany
     */
    public function userPosts(): HasMany
    {
        return $this->hasMany(SystemUserPost::class, 'user_id');
    }

    /**
     * UserOrganization 模型的一对多关联关系
     *
     * @return \think\model\relation\HasMany
     */
    public function userOrgz(): HasMany
    {
        return $this->hasMany(SystemUserOrganization::class, 'user_id');
    }

    /**
     * 用户账号搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     * @param                 $data
     */
    public function searchAccountAttr(Query $query, $value, $data)
    {
        if (!empty($value)) {
            $query->where('account', 'like', $value . '%');
        }
    }

    /**
     * 用户名搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     * @param                 $data
     */
    public function searchNameAttr(Query $query, $value, $data)
    {
        if (!empty($value)) {
            $query->where('user_name', 'like', $value . '%');
        }
    }

    /**
     * 权限数据
     *
     * @param $value
     *
     * @return false|string[]
     */
    public static function getRolesAttr($value): array|bool
    {
        return explode(',', $value);
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
            $query->whereLike('account|user_name', '%' . $value . '%');
        }
    }

    /**
     * 管理员权限搜索器
     *
     * @param \think\db\Query $query
     * @param                 $roles
     */
    public function searchRolesAttr(Query $query, $roles)
    {
        if ($roles) {
            $query->where("CONCAT(',',roles,',')  LIKE '%,$roles,%'");
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
            $query->where('is_enable', $value);
        }
    }

    /**
     * 手机号搜索器
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchPhoneAttr($query, $value, $data)
    {
        if ($value != '' && $value != null) {
            $query->where('cell_phone_number', $value);
        }
    }
}
