<?php

namespace app\model\system\setting;


use phoenix\basic\BaseModel;
use phoenix\traits\ModelTrait;

/**
 * 管理员权限规则
 * Class Permission
 *
 * @package app\model\admin
 */
class SystemRoleOrganization extends BaseModel
{
    use ModelTrait;


    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'role_organization';

}
