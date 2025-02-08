<?php

namespace app\model\system\setting;

use app\common\traits\JwtAuthModelTrait;
use app\common\traits\ModelTrait;
use app\model\BaseModel;

/**
 * 管理员权限规则
 * Class Permission
 *
 * @package app\model\admin
 */
class SystemRolePermission extends BaseModel
{
    use ModelTrait;

    protected $connection = 'rbac';

    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'role_permission';

    /**
     * 用户名搜索器
     *
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchIdAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->whereIn('role_id', $value);
        }
    }

}
