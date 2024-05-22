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

use app\dao\system\admin\SystemDeptDao;
use support\Container;

/**
 * Class SystemRoleServices
 *
 * @package app\services\system\admin
 * @method update($id, array $data, ?string $key = null) 修改数据
 * @method save(array $data) 保存数据
 * @method get(int $id, ?array $field = []) 获取数据
 */
class SystemDeptServices extends BaseServices
{

    /**
     * 当前管理员权限缓存前缀
     */
    const ADMIN_RULES_LEVEL = 'Admin_dept_level_';

    /**
     * SystemRoleServices constructor.
     */
    public function __construct()
    {
        $this->dao = Container::make(SystemDeptDao::class);
    }

    /**
     * 获取后台菜单树型结构列表
     *
     * @param array $where
     * @param array $field
     *
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(array $where, array $field = ['*']): array
    {
        [$page, $limit] = $this->getPageValue(false);
        $deptList = $this->dao->getDeptList($where, $page, $limit);
        $deptList = $this->getDeptsData($deptList);
        return get_tree_children($deptList, 'children', 'dept_id', 'pid');
    }

    /**
     * 获取一条数据
     *
     * @param string $id
     *
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException|\ReflectionException
     */
    public function find(string $id): mixed
    {
        $roleInfo = $this->dao->get($id);
        if (!$roleInfo) {
            throw new AdminException(100026);
        }
        /** @var StoreOrderServices $orderServices */
//        $systemRoleMenu  = app()->make(SystemRoleMenuServices::class);
//        $roleInfo->rules = $systemRoleMenu->column(['role_id' => $id], 'menu_id');
        return $roleInfo->getData();
    }

    /**
     * 删除部门
     *
     * @param string $id
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function delete(string $id): mixed
    {
        $ids = $this->dao->column(['pid' => $id], 'dept_id');
        if (count($ids)) {
            foreach ($ids as $value) {
                $this->delete($value);
            }
        }
        return $this->dao->delete($id);
    }

    /**
     * 获取组织没有被修改器修改的数据
     *
     * @param $deptList
     *
     * @return array
     */
    private function getDeptsData($deptList): array
    {

        $data = [];
        foreach ($deptList as $item) {
            $item = $item->getData();
//            if (isset($item['menu_path'])) {
//                $item['menu_path'] = '/' . config('app.admin_prefix', 'admin') . $item['menu_path'];
//            }
            $data[] = $item;
        }

        return $data;
    }
}
