<?php

namespace app\adminapi\controller\v1\setting;

use app\adminapi\controller\AuthController;
use app\services\system\admin\SystemDeptServices;
use phoenix\services\CacheService;
use think\App;

/**
 * 组织管理
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemDept extends AuthController
{

    public function __construct(App $app, SystemDeptServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 显示资源列表
     *
     * @return \think\response\Json
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(): \think\response\Json
    {
        $where = $this->request->getMore([
            ['status', ''],
            ['keyword', ''],
            ['dept_type', ''],
        ]);
        return app('json')->success($this->services->getList($where, ['*']));

    }

    /**
     * 获取一条信息
     *
     * @param string $id
     *
     * @return \think\Response
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function read(string $id): \think\Response
    {
        if (!$id) {
            return app('json')->fail(100026);
        }
        return app('json')->success($this->services->find($id));
    }

    /**
     * 保存新建的资源
     *
     * @return \think\response\Json
     */
    public function save(): \think\response\Json
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

        if (!$this->services->save($data)) return app('json')->fail(100006);
        $this->services->cacheDriver()->clear();
        return app('json')->success(100000);

    }

    /**
     * 删除指定资源
     *
     * @param                     $id
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function delete($id): \think\response\Json
    {
        if (!$id) {
            return app('json')->fail(100100);
        }
        if (!$this->services->delete($id)) {
            return app('json')->fail(100008);
        } else {
            $this->services->cacheDriver()->clear();
            return app('json')->success(100002);
        }
    }

    /**
     * 修改部门
     *
     * @param $id
     *
     * @return \think\response\Json
     */
    public function update($id): \think\response\Json
    {
        if (!$id || !($role = $this->services->get($id))) {
            return app('json')->fail(100026);
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
            return app('json')->success(100001);
        } else {
            return app('json')->fail(100007);
        }
    }

    /**
     * 修改状态
     *
     * @param $id
     * @param $status
     *
     * @return mixed
     */
    public function set_status($id, $status): \think\response\Json
    {
        if (!$id) {
            return app('json')->fail(100100);
        }
        $role = $this->services->get($id);
        if (!$role) {
            return app('json')->fail(400199);
        }
        $role->status = $status;
        if ($role->save()) {
            $this->services->cacheDriver()->clear();
            return app('json')->success(100001);
        } else {
            return app('json')->fail(100007);
        }
    }
}
