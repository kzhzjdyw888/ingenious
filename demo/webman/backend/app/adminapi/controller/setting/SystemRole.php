<?php

namespace app\adminapi\controller\setting;

use app\adminapi\controller\AuthController;
use app\common\Json;
use app\services\system\admin\SystemMenusServices;
use app\services\system\admin\SystemRoleServices;
use support\Container;
use support\Request;

/**
 * 角色管理
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemRole extends AuthController
{

    public function __construct()
    {
        parent::__construct();
        $this->services = Container::make(SystemRoleServices::class);
    }

    /**
     * 显示资源列表
     *
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(Request $request): \support\Response
    {
        $where = $this->request->getMore([
            ['status', ''],
            ['role_name', ''],
        ]);
        return Json::success($this->services->getRoleList($where));
    }

    /**
     * 获取一条角色信息
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
     * 保存新建的资源
     */
    public function save(Request $request): \support\Response
    {

        $data = $request->postMore([
            'role_code',
            'role_name',
            ['pid', '-1'],
            ['icon', ''],
            ['remarks', ''],
            ['sort', 10],
            ['status', 0],
        ]);

        $message = [
            'role_name' => '400220',
        ];

        $this->validate($data, [
            'role_name' => 'require',
        ], $message, true);

        if (!$this->services->save($data)) return Json::fail(400223);
        $this->services->cacheDriver()->clear();
        return Json::success(400222);

    }

    /**
     * 删除指定资源
     *
     * @param \support\Request $request
     *
     * @return mixed
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
            $this->services->cacheDriver()->clear();
            return Json::success(100002);
        }
    }

    /**
     * 修改角色
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function update(Request $request): \support\Response
    {
        $id = $request->input('id');
        if (!$id || !($role = $this->services->get($id))) {
            return Json::fail(100026);
        }
        $data = $this->request->postMore([
            'role_code',
            'role_name',
            ['pid', '-1'],
            ['icon', ''],
            ['remarks', ''],
            ['sort', 10],
            ['status', 0],
        ]);
        if (!$data['role_name']) {
            return Json::fail(400220);
        }
        if ($this->services->update($id, $data)) {
            return Json::success(100001);
        } else {
            return Json::fail(100007);
        }
    }

    /**
     * 修改状态
     *
     * @param \support\Request $request
     *
     * @return mixed
     */
    public function set_status(Request $request): \support\Response
    {
        $id     = $request->input('id');
        $status = $request->input('status');
        if (!$id) {
            return Json::fail(100100);
        }
        $role = $this->services->get($id);
        if (!$role) {
            return Json::fail(400199);
        }
        $role->status = $status;
        if ($role->save()) {
            $this->services->cacheDriver()->clear();
            return Json::success(100001);
        } else {
            return Json::fail(100007);
        }
    }

    public function menuList(Request $request): \support\Response
    {
        $services = Container::make(SystemMenusServices::class);
        $menus    = $services->getMenus($this->adminInfo['level'] == 0 ? [] : $this->adminInfo['role_id']);
        return Json::success($menus);
    }
}
