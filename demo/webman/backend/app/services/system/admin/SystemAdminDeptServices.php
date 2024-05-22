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

namespace app\services\system\admin;

use app\dao\system\admin\SystemAdminDeptDao;
use app\services\BaseServices;
use support\Container;

/**
 * Class SystemRoleServices
 *
 * @package app\services\system\admin
 * @method update($id, array $data, ?string $key = null) 修改数据
 * @method save(array $data) 保存数据
 * @method get(int $id, ?array $field = []) 获取数据
 */
class SystemAdminDeptServices extends BaseServices
{

    /**
     * 当前管理员权限缓存前缀
     */
    const ADMIN_RULES_LEVEL = 'Admin_admin_dept_level_';

    /**
     * SystemRoleServices constructor.
     */
    public function __construct()
    {
        $this->dao = Container::make(SystemAdminDeptDao::class);
    }
}
