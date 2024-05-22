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
class SystemRoleOrganization extends BaseModel
{
    use ModelTrait;

    protected $connection = 'rbac';

    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'role_organization';

}
