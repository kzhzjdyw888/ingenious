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

namespace app\dao\system\admin;

use app\dao\BaseDao;
use app\model\system\admin\SystemRoleMenu;

/**
 * 角色权限
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemRoleMenuDao extends BaseDao
{
    /**
     * 设置模型名
     *
     * @return string
     */
    protected function setModel(): string
    {
        return SystemRoleMenu::class;
    }

    /**
     * 指定条件获取某些角色权限的名称以数组形式返回
     *
     * @param array  $where
     * @param string $field
     * @param string $key
     *
     * @return array
     * @throws \ReflectionException
     */
    public function column(array $where, string $field, string $key = ''): array
    {
        return $this->search($where)->column($field, $key);
    }

}
