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

namespace app\adminapi\controller\wf;

use app\adminapi\controller\AuthController;
use app\adminapi\controller\wf\config\ConfigurationRewrite;
use app\common\Json;
use ingenious\core\ProcessEngines;
use ingenious\ex\LFlowException;
use ingenious\interface\ProcessEnginesInterface;
use support\Request;

class CategoryController extends AuthController
{

    protected ProcessEnginesInterface $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new ProcessEngines(new ConfigurationRewrite([]));
    }

    public function select(Request $request): \support\Response
    {
        $param = $request->getMore([
            ['status', ''],
            ['name', ''],
            ['is_del', 0],
            ['page', 1],
            ['limit', 15],
        ]);
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processTypesService()->page((object)[]);
        return Json::success('获取成功', $result);
    }

    public function typeTree(Request $request): \support\Response
    {
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processTypesService()->selectTree((object)[]);
        return Json::success($result);

    }

    public function read(Request $request): \support\Response
    {
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processTypesService()->findById($request->input('id'));
        if ($result) {
            return Json::success($result->toArray());
        } else {
            return Json::fail('参数错误');
        }
    }

    public function update(Request $request): \support\Response
    {
        $id               = $request->input('id');
        $ingeniousEngines = $this->service;
        if (!$id || !($ingeniousEngines->processTypesService()->findById($id))) {
            return Json::fail('资源不存在');
        }
        $data = $request->postMore([
            ['id', $id],
            ['name', ''],
            ['icon', ''],
            ['pid', '0'],
            ['remark', ''],
            ['sort', 10],
            ['user_id', $request->adminId()],
        ]);
        if ($ingeniousEngines->processTypesService()->update((object)$data)) {
            return Json::success('更新成功');
        } else {
            return Json::fail('更新失败');
        }

    }

    public function delete(Request $request): \support\Response
    {
        $id               = $request->input('id');
        $ingeniousEngines = $this->service;
        if (!$id) return Json::fail('参数ID不能为空');
        try {
            $ingeniousEngines->processTypesService()->del($id);
            return Json::success('删除成功');
        } catch (LFlowException $e) {
            return Json::fail('删除失败' . $e->getMessage());
        }

    }

    public function remove(Request $request): \support\Response
    {
        $ids = $request->input('data');
        if (!$ids) return Json::fail('参数ID不能为空');
        $ingeniousEngines = $this->service;
        try {
            $ingeniousEngines->processTypesService()->del(explode(',', $ids));
            return Json::success('删除成功');
        } catch (LFlowException $e) {
            return Json::fail('删除失败' . $e->getMessage());
        }

    }

    public function save(Request $request): \support\Response
    {
        $ingeniousEngines = $this->service;
        $data             = $request->postMore([
            ['name', ''],
            ['pid', '0'],
            ['icon', ''],
            ['remark', ''],
            ['sort', 10],
            ['create_user', $request->adminId()],
        ]);
        if (empty($data['name'])) {
            return Json::fail('名称不能为空');
        }
        if ($ingeniousEngines->processTypesService()->create((object)$data)) {
            return Json::success('创建成功');
        } else {
            return Json::fail('创建失败');
        }
    }
}

