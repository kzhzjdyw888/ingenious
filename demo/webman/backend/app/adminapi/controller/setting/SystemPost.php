<?php

namespace app\adminapi\controller\setting;

use app\adminapi\controller\AuthController;
use app\common\Json;
use app\services\system\admin\SystemPostServices;
use support\Container;
use support\Request;

/**
 * 职位管理
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemPost extends AuthController
{

    public function __construct()
    {
        parent::__construct();
        $this->services = Container::make(SystemPostServices::class);
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
        $where = $request->getMore([
            ['dept_id', ''],
            ['post_code', ''],
            ['post_name', ''],
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
     */
    public function save(Request $request): \support\Response
    {
        $data = $request->postMore([
            'post_code',
            'post_name',
            ['dept_id'],
            ['pid', '-1'],
            ['icon', ''],
            ['remarks', ''],
            ['sort', 10],
        ]);

        $message = [
            'post_name' => '400187',
            'dept_id'   => '所属部门不能为空',
        ];

        $this->validate($data, [
            'dept_id'   => 'require',
            'post_name' => 'require',
        ], $message, true);
        if (!$this->services->save($data)) {
            return Json::fail(100006);
        }
        $this->services->cacheDriver()->clear();
        return Json::success(100000);

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
        $data    = $this->request->postMore([
            'post_code',
            'post_name',
            'dept_id',
            ['pid', '-1'],
            ['icon', ''],
            ['remarks', ''],
            ['sort', 10],
        ]);
        $message = [
            'post_name' => '400187',
            'dept_id'   => '所属部门不能为空',
        ];

        $this->validate($data, [
            'dept_id'   => 'require',
            'post_name' => 'require',
        ], $message, true);

        if ($this->services->update($id, $data)) {
            return Json::success(100001);
        } else {
            return Json::fail(100007);
        }
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
            $this->services->cacheDriver()->clear();
            return Json::success(100002);
        }
    }

}
