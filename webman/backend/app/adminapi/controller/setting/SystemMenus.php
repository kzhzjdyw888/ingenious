<?php

namespace app\adminapi\controller\setting;

use app\adminapi\controller\AuthController;
use app\common\Json;
use app\services\system\admin\SystemMenusServices;
use app\services\system\route\SystemRouteCateServices;
use app\services\system\route\SystemRouteServices;
use support\Container;
use support\Request;

/**
 * 菜单权限
 * Class SystemMenus
 *
 * @package app\adminapi\controller\v1\setting
 */
class SystemMenus extends AuthController
{
    /**
     * SystemMenus constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->services = Container::make(SystemMenusServices::class);
    }

    /**
     * 菜单展示列表
     *
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException|\ReflectionException
     * @author 吴汐
     * @email  442384644@qq.com
     * @date   2023/05/06
     */
    public function index(Request $request): \support\Response
    {
        $where = $this->request->getMore([
            ['is_show', ''],
            ['keyword', ''],
            ['auth_type', ''],
        ]);
        return Json::success($this->services->getList($where, ['*']));
    }

    /**
     * @param \support\Request $request
     *
     * @return \support\Response
     * @author 等风来
     * @email  136327134@qq.com
     * @date   2023/4/14
     */
    public function unique(Request $request): \support\Response
    {
        $adminInfo = $this->request->adminInfo();
        [$menus, $uniqueAuth] = Container::make(SystemMenusServices::class)->getMenusList($adminInfo['roles'], (int)$adminInfo['level']);
        return Json::success(compact('menus', 'uniqueAuth'));
    }

    /**
     * 菜单获取
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function permission(Request $request): \support\Response
    {
        $adminInfo = $this->request->adminInfo();
        [$menus, $uniqueAuth] = Container::make(SystemMenusServices::class)->getMenusList($adminInfo['roles'] , (int)$adminInfo['level']);
        return Json::success($menus);
    }

    /**
     * 保存菜单权限
     */
    public function save(Request $request): \support\Response
    {
        $data                 = $this->request->getMore([
            ['menu_name', ''],
            ['controller', ''],
            ['module', 'admin'],
            ['action', ''],
            ['icon', ''],
            ['params', ''],
            ['path', []],
            ['menu_path', ''],
            ['api_url', ''],
            ['methods', ''],
            ['unique_auth', ''],
            ['header', ''],
            ['is_header', 0],
            ['pid', 0],
            ['sort', 0],
            ['auth_type', 0],
            ['access', 1],
            ['is_show', 0],
            ['is_show_path', 0],
        ]);
        $data['is_show_path'] = $data['is_show'];
        if (!$data['menu_name'])
            return Json::fail(400198);
        $data['path'] = implode('/', $data['path']);
        if ($this->services->save($data)) {
            return Json::success(100021);
        } else {
            return Json::fail(100022);
        }
    }

    /**
     * 批量保存权限
     *
     * @return \think\Response
     * @author 等风来
     * @email  136327134@qq.com
     * @date   2023/4/11
     */
    public function batchSave(Request $request): \support\Response
    {
        $menus = $this->request->post('menus', []);
        if (!$menus) {
            return Json::fail(100026);
        }
        $data = [];

        $uniqueAuthAll = $this->services->getColumn(['delete_time' => null, 'is_show' => 1], 'unique_auth');
        $uniqueAuthAll = array_filter($uniqueAuthAll, function ($item) {
            return !!$item;
        });
        $uniqueAuthAll = array_unique($uniqueAuthAll);

        $uniqueFn = function ($path) use ($uniqueAuthAll) {
            $attPath    = explode('/', $path);
            $uniqueAuth = '';
            if ($attPath) {
                $pathData = [];
                foreach ($attPath as $vv) {
                    if (!str_contains($vv, '<')) {
                        $pathData[] = $vv;
                    }
                }
                $uniqueAuth = implode('-', $pathData);
            }

            if (in_array($uniqueAuth, $uniqueAuthAll)) {
                $uniqueAuth .= '-' . uniqid();
            }

            $uniqueAuthAll[] = $uniqueAuth;

            return $uniqueAuth;
        };

        foreach ($menus as $menu) {
            if (empty($menu['menu_name'])) {
                return Json::fail(400198);
            }
            if (isset($menu['unique_auth']) && $menu['unique_auth']) {
                $menu['unique_auth'] = explode('/', $menu['api_url']);
            }
            $data[] = [
                'methods'      => $menu['method'],
                'menu_name'    => $menu['menu_name'],
                'unique_auth'  => !empty($menu['unique_auth']) ? $menu['unique_auth'] : $uniqueFn($menu['api_url']),
                'api_url'      => $menu['api_url'],
                'pid'          => $menu['path'],
                'auth_type'    => 2,
                'is_show'      => 1,
                'is_show_path' => 1,
            ];
        }
        $this->services->saveAll($data);
        return Json::success(100021);
    }

    /**
     * 获取一条菜单权限信息
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function read(Request $request): \support\Response
    {
        $id = $request->input('id');
        if (!$id) {
            return Json::fail(100026);
        }
        return Json::success($this->services->find($id));
    }

    /**
     * 修改菜单权限表单获取
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function edit(Request $request): \support\Response
    {
        $id = $request->input('id');
        if (!$id) {
            return Json::fail(100100);
        }
        return Json::success($this->services->updateMenus((int)$id));
    }

    /**
     * 修改菜单
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function update(Request $request): \support\Response
    {
        $id = $request->input('id');
        if (!$id || !($menu = $this->services->get($id)))
            return Json::fail(100026);
        $data = $this->request->postMore([
            'menu_name',
            'controller',
            ['module', 'admin'],
            'action',
            'params',
            ['icon', ''],
            ['menu_path', ''],
            ['api_url', ''],
            ['methods', ''],
            ['unique_auth', ''],
            ['path', []],
            ['sort', 0],
            ['pid', 0],
            ['is_header', 0],
            ['header', ''],
            ['auth_type', 0],
            ['access', 1],
            ['is_show', 0],
            ['is_show_path', 0],
        ]);
        if (!$data['menu_name'])
            return Json::fail(400198);
        $data['path'] = implode('/', $data['path']);
        if ($this->services->update($id, $data))
            return Json::success(100001);
        else
            return Json::fail(100007);
    }

    /**
     * 删除指定资源
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function delete(Request $request): \support\Response
    {
        $id = $request->input('id');
        if (!$id) {
            return Json::fail(100100);
        }

        if (!$this->services->delete($id)) {
            return Json::fail(100008);
        } else {
            return Json::success(100002);
        }
    }

    /**
     * 权限的开启和关闭，显示和隐藏
     *
     * @param $id
     *
     * @return mixed
     */
    public function show(Request $request): \support\Response
    {
        $id = $request->input('id');
        if (!$id) {
            return Json::fail(100100);
        }

        [$isShow, $isShowPath] = $request->postMore([['is_show', 0], ['is_show_path', 0]], true);
        if ($isShow == -1) {
            $res = $this->services->update($id, ['is_show_path' => $isShowPath]);
        } else {
            $res = $this->services->update($id, ['is_show' => $isShow, 'is_show_path' => $isShow]);
        }

        if ($res) {
            return Json::success(100001);
        } else {
            return Json::fail(100007);
        }
    }

    /**
     * 获取菜单数据
     *
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException|\ReflectionException
     */
    public function menus(Request $request): \support\Response
    {
        [$menus, $unique] = $this->services->getMenusList($this->adminInfo['roles'], (int)$this->adminInfo['level']);
        return Json::success(['menus' => $menus, 'unique' => $unique]);
    }

    /**
     * 获取路由分类
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     * @author 等风来
     * @email  136327134@qq.com
     * @date   2023/4/25
     */
    public function ruleCate(Request $request): \support\Response
    {
        $service = Container::make(SystemRouteCateServices::class);
        return Json::success($service->getAllList('adminapi'));
    }

    /**
     * 获取接口列表
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function ruleList(Request $request): \support\Response
    {
        $cateId = $request->input('cate_id', 0);
        //获取所有的路由
        $services = Container::make(SystemRouteServices::class);
        $ruleList = $services->selectList(['cate_id' => $cateId, 'app_name' => 'adminapi'])->toArray();
        return Json::success($ruleList);
    }
}
