<?php
/**
 *+------------------
 * Lflow
 *+------------------
 * Copyright (c) 2023~2030 gitee.com/liu_guan_qing All rights reserved.本版权不可删除，侵权必究
 *+------------------
 * Author: Mr.April(405784684@qq.com)
 *+------------------
 */

namespace app\adminapi\controller\v1\system;

use app\adminapi\controller\AuthController;
use app\jobs\TestJob;
use app\Request;
use app\services\system\dict\SystemDictServices;
use think\App;
use think\facade\Queue;

class SystemDict extends AuthController
{

    /**
     * 构造方法
     * SystemLog constructor.
     *
     * @param App                                          $app
     * @param \app\services\system\dict\SystemDictServices $services
     */
    public function __construct(App $app, SystemDictServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * index
     *
     * @param \app\Request $request
     *
     * @return \think\response\Json
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(Request $request): \think\response\Json
    {
        $where = $request->getMore([['name', '']]);
        return app('json')->success($this->services->getDictList($where));
    }

    /**
     * 添加字典
     *
     * @param \app\Request $request
     *
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function save(Request $request): \think\response\Json
    {
        $data = $request->postMore([
            ['name', ''],
            ['value', ''],
        ]);

        $message = [
            'name' => '400220',
        ];
        $this->validate($data, [
            'name' => 'require',
        ], $message, true);
        if ($this->services->saveDict($data['name'], $data['value'])) {
            return app('json')->success(100000);
        } else {
            return app('json')->fail(100006);
        }
    }

    /**
     * 获取详情
     *
     * @param $id
     *
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function read($id): \think\response\Json
    {
        if (empty($id)) {
            return app('json')->fail(100100);
        }
        return app('json')->success($this->services->read($id));
    }

    /**
     * 更新
     *
     * @param \app\Request $request
     * @param              $id
     *
     * @return \think\response\Json
     */
    public function update(Request $request, $id): \think\response\Json
    {
        if (empty($id)) {
            return app('json')->fail(100100);
        }
        $data = $request->postMore([
            ['name', ''],
            ['value', ''],
        ]);

        $message = [
            'name' => '400220',
        ];
        $this->validate($data, [
            'name' => 'require',
        ], $message, true);

        if ($this->services->updateDict($id, $data)) {
            return app('json')->success(100000);
        } else {
            return app('json')->fail(100006);
        }

    }

    /**
     * 删除字典
     *
     * @param \app\Request $request
     * @param              $id
     *
     * @return \think\response\Json
     */
    public function delete(Request $request, $id): \think\response\Json
    {
        if (empty($id)) {
            return app('json')->fail(100100);
        }
        if ($this->services->delete($id, 'id')) {
            return app('json')->success(100002);
        } else {
            return app('json')->fail(100008);
        }
    }

    /**
     * 批量删除
     *
     * @param \app\Request $request
     *
     * @return \think\response\Json
     */
    public function batchDelete(Request $request): \think\response\Json
    {
        $data = $request->post('data', []);
        if (empty($data)) {
            return app('json')->fail(100100);
        }
        foreach ($data as $value) {
            $this->services->delete($value, 'id');
        }
        return app('json')->success(100002);
    }

    /**
     * 获取字典详情
     *
     * @param $name
     *
     * @return \think\response\Json
     */
    public function get($name): \think\response\Json
    {
        $data          = $this->services->getName($this->services->dictNameToOptionName($name));
        $data['value'] = json_decode($data['value']);
        return app('json')->success($data);
    }

}
