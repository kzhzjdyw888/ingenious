<?php

namespace app\adminapi\controller\v1\setting;

use app\adminapi\controller\AuthController;
use app\services\system\admin\SystemPostServices;
use phoenix\services\CacheService;
use think\App;

/**
 * 职位管理
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemPost extends AuthController
{

    public function __construct(App $app, SystemPostServices $services)
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
            ['dept_id',''],
            ['post_code', ''],
            ['post_name', ''],
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
            return app('json')->fail(100006);
        }
        $this->services->cacheDriver()->clear();
        return app('json')->success(100000);

    }

    /**
     * 修改角色
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
            return app('json')->success(100001);
        } else {
            return app('json')->fail(100007);
        }
    }

    /**
     * 删除指定资源
     *
     * @param $id
     *
     * @return \think\response\Json
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

}
