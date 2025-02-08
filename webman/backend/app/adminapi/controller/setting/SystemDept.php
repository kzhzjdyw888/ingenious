<?php

namespace app\adminapi\controller\setting;

use app\adminapi\controller\AuthController;
use app\common\Json;
use app\services\system\admin\SystemDeptServices;
use support\Container;
use support\Request;

/**
 * 组织管理
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemDept extends AuthController
{

    public function __construct()
    {
        parent::__construct();
        $this->services = Container::make(SystemDeptServices::class);
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
            ['keyword', ''],
            ['dept_type', ''],
        ]);
        return Json::success($this->services->getList($where, ['*']));

    }

    /**
     * 获取一条信息
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
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function save(Request $request): \support\Response
    {
        $data = $this->request->postMore([
            ['dept_code'],
            ['dept_name'],
            ['dept_type', 1],
            ['pid', '-1'],
            ['icon', ''],
            ['remarks', ''],
            ['sort', 10],
            ['status', 0],
        ]);

        $message = [
            'dept_name' => '部门名称不能为空',
        ];

        $this->validate($data, [
            'dept_name' => 'require',
        ], $message, true);

        if (!$this->services->save($data)) return Json::fail(100006);
        $this->services->cacheDriver()->clear();
        return Json::success(100000);

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
     * 修改部门
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
            ['dept_code'],
            ['dept_name'],
            ['dept_type', 1],
            ['pid', '-1'],
            ['icon', ''],
            ['remarks', ''],
            ['sort', 10],
            ['status', 0],
        ]);

        $message = [
            'dept_name' => '部门名称不能为空',
        ];

        $this->validate($data, [
            'dept_name' => 'require',
        ], $message, true);

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
}
