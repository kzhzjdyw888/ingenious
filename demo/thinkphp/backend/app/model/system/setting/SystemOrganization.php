<?php

namespace app\model\system\setting;

use phoenix\basic\BaseModel;
use phoenix\traits\ModelTrait;
use Ramsey\Uuid\Uuid;
use think\model\concern\SoftDelete;
use think\model\relation\HasMany;

class SystemOrganization extends BaseModel
{

    use ModelTrait;

    /**
     * 数据表主键
     *
     * @var string
     */
    protected $pk = 'org_id';

    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'organization';

    protected $deleteTime = 'delete_time';

    /**
     * 软删除
     */
    use SoftDelete;

    /**
     * 定义与UserPost模型的一对多关联关系0808
     *
     * @return \think\model\relation\HasMany
     */
    public function userOrg(): HasMany
    {
        return $this->hasMany(SystemUserPost::class, 'org_id');
    }

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
     * 部门Aidin搜索器
     *
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchOrgIdInAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->whereIn('org_id', 'in', $value);
        }
    }
}