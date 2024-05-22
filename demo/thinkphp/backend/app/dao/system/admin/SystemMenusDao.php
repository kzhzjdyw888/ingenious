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
use app\model\system\admin\SystemMenus;

/**
 *
 * 菜单Dao层
 * @author Mr.April
 * @since  1.0
 */
class SystemMenusDao extends BaseDao
{

    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return SystemMenus::class;
    }

    /**
     *
     * @param array $menusIds
     *
     * @return bool
     */
    public function deleteMenus(array $menusIds): bool
    {
        return $this->getModel()->whereIn('id', $menusIds)->delete();
    }

    /**
     * 获取菜单列表
     *
     * @param array      $where
     * @param array|null $field
     *
     * @return \think\Collection
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getMenusRoule(array $where, ?array $field = []): \think\Collection
    {
        if (!$field) {
            $field = ['id', 'menu_name', 'icon', 'pid', 'sort', 'menu_path', 'auth_type','is_show', 'header', 'is_header', 'is_show_path', 'is_show'];
        }
        return $this->search($where)->field($field)->order('sort ASC')->failException(false)->select();
    }

    /**
     * 获取菜单中的唯一权限
     *
     * @param array $where
     *
     * @return array
     * @throws \ReflectionException
     */
    public function getMenusUnique(array $where): array
    {
        return $this->search($where)->where('unique_auth', '<>', '')->column('unique_auth', '');
    }

    /**
     * 根据访问地址获得菜单名
     *
     * @param string $rule
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function getVisitName(string $rule): mixed
    {
        return $this->search(['url' => $rule])->value('menu_name');
    }

    /**
     * 获取后台菜单列表并分页
     *
     * @param array $where
     * @param array $field
     *
     * @return \think\Collection
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getMenusList(array $where, array $field = ['*']): \think\Collection
    {
        $where = array_merge($where, ['delete_time' => null]);
        return $this->search($where)->field($field)->order('sort DESC,id ASC')->select();
    }

    /**
     * 菜单总数
     *
     * @param array $where
     *
     * @return int
     * @throws \ReflectionException
     */
    public function countMenus(array $where): int
    {
        $where = array_merge($where, ['delete' => null]);
        return $this->count($where);
    }

    /**
     * 指定条件获取某些菜单的名称以数组形式返回
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

    /**菜单列表
     *
     * @param array $where
     * @param int $type
     *
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException|\ReflectionException
     */
    public function menusSelect(array $where, $type = 1): \think\Collection
    {
        if ($type == 1) {
            return $this->search($where)->field('id,pid,menu_name,menu_path,unique_auth,sort')->order('sort DESC,id DESC')->select();
        } else {
            return $this->search($where)->group('pid')->column('pid');
        }
    }

    /**
     * 搜索列表
     *
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException|\ReflectionException
     */
    public function getSearchList(): \think\Collection|array
    {
        return $this->search(['is_show' => 1, 'auth_type' => 1, 'delete_time' => null, 'is_show_path' => 0])
            ->field('id,pid,menu_name,menu_path,unique_auth,sort')->order('sort DESC,id DESC')->select();
    }


    /**
     *
     * @param string $path
     * @param string $method
     *
     * @return bool
     */
    public function deleteMenu(string $path, string $method): bool
    {
        return $this->getModel()->where('api_url', $path)->where('methods', $method)->delete();
    }
}
