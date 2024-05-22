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

namespace app\adminapi\controller\v1\wf;

use app\adminapi\controller\AuthController;
use app\adminapi\controller\v1\wf\config\ConfigurationRewrite;
use app\Request;
use ingenious\core\ProcessEngines;
use ingenious\ex\LFlowException;
use ingenious\interface\ProcessEnginesInterface;
use lflow\core\services\CategoryServices;
use think\App;

class Category extends AuthController
{

    protected ProcessEnginesInterface $service;

    public function __construct(App $app, $config = [])
    {
        parent::__construct($app);
        $this->service = new ProcessEngines(new ConfigurationRewrite($config));
    }

    public function index(): \think\response\Json
    {
        $param            = $this->request->getMore([
            ['status', ''],
            ['name', ''],
            ['is_del', 0],
            ['page', 1],
            ['limit', 15],
        ]);
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processTypesService()->page((object)$param);
        return app('json')->success($result);
    }

    public function read(string $id): \think\Response\Json
    {
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processTypesService()->findById($id);
        if ($result) {
            return app('json')->success($result->toArray());
        } else {
            return app('json')->fail('参数错误');
        }
    }

    public function save(Request $request): \think\Response\Json
    {
        $ingeniousEngines = $this->service;
        $data             = $this->request->postMore([
            ['name', ''],
            ['pid', '0'],
            ['icon', ''],
            ['remark', ''],
            ['sort', 10],
            ['create_user', $request->adminId()],
        ]);
        if (empty($data['name'])) {
            return app('json')->fail('名称不能为空');
        }
        if ($ingeniousEngines->processTypesService()->create((object)$data)) {
            return app('json')->success('创建成功');
        } else {
            return app('json')->fail('创建失败');
        }
    }

    public function delete(string $id): \think\Response\Json
    {
        $ingeniousEngines = $this->service;
        if (!$id) return app('json')->fail('参数ID不能为空');
        try {
            $ingeniousEngines->processTypesService()->del($id);
            return app('json')->success('删除成功');
        } catch (LFlowException $e) {
            return app('json')->fail('删除失败' . $e->getMessage());
        }
    }

    public function batchRemove(): \think\Response\Json
    {
        $ids = $this->request->post('data');
        if (!$ids) return app('json')->fail('参数ID不能为空');
        $ingeniousEngines = $this->service;
        try {
            $ingeniousEngines->processTypesService()->del(explode(',', $ids));
            return app('json')->success('删除成功');
        } catch (LFlowException $e) {
            return app('json')->fail('删除失败' . $e->getMessage());
        }
    }

    public function update(Request $request, string $id): \think\Response\Json
    {
        $ingeniousEngines = $this->service;
        if (!$id || !($ingeniousEngines->processTypesService()->findById($id))) {
            return app('json')->fail('资源不存在');
        }
        $data = $this->request->postMore([
            ['id', $id],
            ['name', ''],
            ['icon', ''],
            ['pid', '0'],
            ['remark', ''],
            ['sort', 10],
            ['user_id', $request->adminId()],
        ]);
        if ($ingeniousEngines->processTypesService()->update((object)$data)) {
            return app('json')->success('更新成功');
        } else {
            return app('json')->fail('更新失败');
        }
    }


    public function typeTree()
    {
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processTypesService()->selectTree((object)[]);
        return app('json')->success($result);
    }
}
