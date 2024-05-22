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
use app\model\system\admin\SystemDept;

/**
 * 角色管理Dao层
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemDeptDao extends BaseDao
{
    /**
     * 设置模型名
     *
     * @return string
     */
    protected function setModel(): string
    {
        return SystemDept::class;
    }

    /**
     * 获取组织列表
     *
     * @param array  $where
     * @param int    $page
     * @param int    $limit
     * @param string $sort
     *
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getDeptList(array $where, int $page, int $limit, string $sort = 'sort asc'): \think\Collection|array
    {
        return $this->search($where)->page($page, $limit)->order($sort)->select();
    }

    /**
     * 指定条件获取某些组织的名称以数组形式返回
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
