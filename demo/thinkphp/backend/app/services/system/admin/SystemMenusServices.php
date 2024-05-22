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

use app\dao\system\admin\SystemMenusDao;
use app\services\BaseServices;
use app\services\system\setting\SystemRoleServices;
use phoenix\exceptions\AdminException;
use phoenix\utils\Arr;

/**
 * 权限菜单
 * Class SystemMenusServices
 *
 * @package app\services\system
 * @method save(array $data) 保存数据
 * @method get(int $id, ?array $field = []) 获取数据
 * @method update($id, array $data, ?string $key = null) 修改数据
 * @method getSearchList() 主页搜索
 * @method getColumn(array $where, string $field, ?string $key = '') 主页搜索
 * @method getVisitName(string $rule) 根据访问地址获得菜单名
 */
class SystemMenusServices extends BaseServices
{

    /**
     * 初始化
     * SystemMenusServices constructor.
     *
     * @param SystemMenusDao $dao
     */
    public function __construct(SystemMenusDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 获取菜单没有被修改器修改的数据
     *
     * @param $menusList
     *
     * @return array
     */
    public function getMenusData($menusList): array
    {
        $data = [];
        foreach ($menusList as $item) {
            $item = $item->getData();
//            if (isset($item['menu_path'])) {
//                $item['menu_path'] = '/' . config('app.admin_prefix', 'admin') . $item['menu_path'];
//            }
            $data[] = $item;
        }

        return $data;
    }

    /**
     * 获取后台权限菜单和权限
     *
     * @param array|string $menuId
     * @param int          $level
     *
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getMenusList(array|string $menuId, int $level = 1): array
    {
        /** @var SystemRoleServices $systemRoleServices */
        $menusList = $this->dao->getMenusRoule(['route' => $level != 0 ? $menuId : '', 'is_show' => 1]);
        $unique    = $this->dao->getMenusUnique(['unique' => $level != 0 ? $menuId : '']);
        return [Arr::getMenuLayuiViewList($this->getMenusData($menusList)), $unique];

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
        $menusList = $this->dao->getMenusList($where, $field);
        $menusList = $this->getMenusData($menusList);
        return get_tree_children($menusList);
    }

    /**
     * 获取form表单所需要的所要的菜单列表
     *
     * @return array[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException|\ReflectionException
     */
    protected function getFormSelectMenus(): array
    {
        $menuList = $this->dao->getMenusRoule(['delete_time' => null], ['id', 'pid', 'menu_name']);
        $list     = sort_list_tier($this->getMenusData($menuList), '0', 'pid', 'id');
        $menus    = [['value' => 0, 'label' => '顶级按钮']];
        foreach ($list as $menu) {
            $menus[] = ['value' => $menu['id'], 'label' => $menu['html'] . $menu['menu_name']];
        }
        return $menus;
    }

    /**
     * @param string $value
     * @param int    $auth_type
     *
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected function getFormCascaderMenus(string $value = '-1', int $auth_type = 0): array
    {
        $where    = ['delete_time' => null];
        $menuList = $this->dao->getMenusRoule($where, ['id as value', 'pid', 'menu_name as label']);
        $menuList = $this->getMenusData($menuList);
        if ($value) {
            $data = get_tree_value($menuList, $value);
        } else {
            $data = [];
        }
        return [get_tree_children($menuList, 'children', 'value'), array_reverse($data)];
    }

    /**
     * 修改权限菜单
     *
     * @param string $id
     *
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function updateMenus(string $id): array
    {
        $menusInfo = $this->dao->get($id);
        if (!$menusInfo) {
            throw new AdminException(100026);
        }
        return create_form('修改权限', $this->createMenusForm($menusInfo->getData()), $this->url('/setting/update/' . $id), 'PUT');
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
        $menusInfo = $this->dao->get($id);
        if (!$menusInfo) {
            throw new AdminException(100026);
        }
        $menu                 = $menusInfo->getData();
        $menu['pid']          = $menu['pid'];
        $menu['auth_type']    = (int)$menu['auth_type'];
        $menu['is_header']    = (int)$menu['is_header'];
        $menu['is_show']      = (int)$menu['is_show'];
        $menu['is_show_path'] = (int)$menu['is_show_path'];
        if (!$menu['path']) {
            [$menuList, $data] = $this->getFormCascaderMenus($menu['pid']);
            $menu['path'] = $data;
        } else {
            $menu['path'] = explode('/', $menu['path']);
            if (is_array($menu['path'])) {
                $menu['path'] = array_map(function ($item) {
                    return (int)$item;
                }, $menu['path']);
            }
        }
        return $menu;
    }

    /**
     * 删除菜单
     *
     * @param string $id
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function delete(string $id): mixed
    {
        $ids = $this->dao->column(['pid' => $id], 'id');
        if (count($ids)) {
            foreach ($ids as $value) {
                $this->delete($value);
            }
        }
        return $this->dao->delete($id);
    }

    /**
     * 获取添加身份规格
     *
     * @param $roleId
     *
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getMenus($roleId): array
    {
        $field = ['menu_name', 'pid', 'id'];
        $where = ['delete_time' => null, 'is_show' => 1];
        if (!$roleId) {
            $menus = $this->dao->getMenusRoule($where, $field);
        } else {
            /** @var SystemRoleServices $service */
            $service = app()->make(SystemRoleMenuServices::class);
            $roles   = is_string($roleId) ? explode(',', $roleId) : $roleId;
            $ids     = $service->getRoleIds($roles);
            $menus   = $this->dao->getMenusRoule(['rule' => $ids] + $where, $field);
        }
        return $this->tidyMenuTier(false, $menus);
    }

    /**
     * 组合菜单数据
     *
     * @param bool         $adminFilter
     * @param array|object $menusList
     * @param string       $pid
     * @param array        $navList
     *
     * @return array
     */
    public function tidyMenuTier(bool $adminFilter = false, array|object $menusList = [], string $pid = '-1', array $navList = []): array
    {
        foreach ($menusList as $k => $menu) {
            $menu          = $menu->getData();
            $menu['title'] = $menu['menu_name'];
            unset($menu['menu_name']);
            if ($menu['pid'] == $pid) {
                unset($menusList[$k]);
                $menu['children'] = $this->tidyMenuTier($adminFilter, $menusList, $menu['id']);
                if ($pid == 0 && !count($menu['children'])) continue;
                if ($menu['children']) $menu['expand'] = true;
                $navList[] = $menu;
            }
        }
        return $navList;
    }
}
