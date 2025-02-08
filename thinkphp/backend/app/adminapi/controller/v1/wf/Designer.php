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
use app\services\system\admin\SystemAdminServices;
use ingenious\core\ProcessEngines;
use ingenious\enums\ProcessConst;
use ingenious\ex\LFlowException;
use ingenious\interface\ProcessEnginesInterface;
use think\App;
use think\facade\Db;

class Designer extends AuthController
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
            ['id', ''],
            ['model_key', ''],
            ['model_name', ''],
            ['model_group_id', ''],
            ['page',1],
            ['limit',10]
        ]);
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processDesignService()->page((object)$param);
        return app('json')->success($result);
    }

    public function read(string $id): \think\Response\Json
    {
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processDesignService()->findById($id);
        if ($result != null) {
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
            ['display_name', ''],
            ['description', ''],
            ['type_id', ''],
            ['icon', ''],
            ['remark', ''],
            ['create_user', $request->adminId()],
        ]);
        if (empty($data['name'])) {
            return app('json')->fail('唯一编码不能为空');
        }
        if (empty($data['display_name'])) {
            return app('json')->fail('显示名称不能为空');
        }
        if ($ingeniousEngines->processDesignService()->create((object)$data)) {
            return app('json')->success('添加成功');
        } else {
            return app('json')->fail('添加失败');
        }
    }

    public function updateDefine(): \think\Response\Json
    {
        $data                            = $this->request->param();
        $data[ProcessConst::CREATE_USER] = \request()->adminId();//追加用户id
        $ingeniousEngines                = $this->service;
        $result                          = $ingeniousEngines->processDesignService()->updateDefine((object)$data);
        if ($result) {
            return app('json')->success('保存成功');
        } else {
            return app('json')->fail('保存失败');
        }
    }

    public function delete(string $id): \think\Response\Json
    {
        $ingeniousEngines = $this->service;
        if (!($ingeniousEngines->processDesignService()->del($id))) {
            return app('json')->fail(100008);
        }
        return app('json')->success('删除成功');
    }

    public function batchRemove(): \think\Response\Json
    {
        $data             = $this->request->post('data');
        $ingeniousEngines = $this->service;
        $error            = [];
        foreach (explode(',', $data) as $key => $value) {
            $ret = $ingeniousEngines->processDesignService()->del($value);
            if (!$ret) {
                $error[] = $value . '删除失败';
            }
        }
        if (count($error) > 0) {
            return app('json')->fail(import(',', $error));
        }
        return app('json')->success('删除成功');
    }

    public function update(Request $request, string $id): \think\Response\Json
    {
        $ingeniousEngines = $this->service;
        $data             = $this->request->postMore([
            ['id', $id],
            ['name', ''],
            ['display_name', ''],
            ['description', ''],
            ['type_id', ''],
            ['icon', ''],
            ['remark', ''],
            ['update_user', $request->adminId()],
        ]);
        if ($ingeniousEngines->processDesignService()->update((object)$data)) {
            return app('json')->success('更新成功');
        } else {
            return app('json')->fail('更新失败');
        }
    }

    public function deploy(Request $request)
    {
        Db::startTrans();
        try {
            $processDesignid  = $request->param('id');
            $ingeniousEngines = $this->service;
            $ingeniousEngines->processDesignService()->deploy($processDesignid, $request->adminId());
            Db::commit();
            return app('json')->success('部署成功');
        } catch (LFlowException $e) {
            Db::rollback();
            return app('json')->fail($e->getMessage());
        }

    }

    public function redeploy(Request $request)
    {
         Db::startTrans();
        try {
            $processDesignid  = $request->param('id');
            $ingeniousEngines = $this->service;
            $ingeniousEngines->processDesignService()->redeploy($processDesignid, $request->adminId());
            Db::commit();
            return app('json')->success('重新部署成功');
        } catch (LFlowException $e) {
            Db::rollback();
            return app('json')->fail($e->getMessage());
        }

    }
}
