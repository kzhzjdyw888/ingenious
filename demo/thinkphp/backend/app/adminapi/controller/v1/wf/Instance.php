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
use Exception;
use ingenious\core\ProcessEngines;
use ingenious\ex\LFlowException;
use ingenious\interface\ProcessEnginesInterface;
use think\App;
use think\facade\Db;

class Instance extends AuthController
{

    protected ProcessEnginesInterface $service;

    public function __construct(App $app, $config = [])
    {
        parent::__construct($app);
        $this->service = new ProcessEngines(new ConfigurationRewrite($config));
    }

    public function index(Request $request): \think\response\Json
    {
        $args             = $this->request->getMore([
            ['operator', $request->adminId()],
            ['business_no', ''],
            ['display_name', ''],
            ['page', 0],
            ['limit', 0],
        ]);
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processInstanceService()->page((object)$args);
        return app('json')->success($result);
    }

    public function management(Request $request)
    {
        $args             = $this->request->getMore([
            ['business_no', ''],
            ['display_name', ''],
            ['page', 0],
            ['limit', 0],
        ]);
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processInstanceService()->page((object)$args);
        return app('json')->success($result);
    }

    public function detail(Request $request): \think\Response\Json
    {

        $ingeniousEngine = $this->service;
        $id              = $request->param('id');
        $result          = $ingeniousEngine->processInstanceService()->findById($id);
        if ($result == null) {
            return app('json')->fail('实例不存在或被删除');
        }
        return app('json')->success($result->toArray());
    }

    public function withdraw(Request $request)
    {
        Db::startTrans();
        try {
            $ingeniousEngine = $this->service;
            $id              = $request->param('id', '');
            foreach (explode(',', $id) as $value) {
                $ingeniousEngine->processInstanceService()->withdraw($value, $request->adminId());
            }
            Db::commit();
            return app('json')->success('撤回成功');
        } catch (LFlowException $e) {
            Db::rollback();
            return app('json')->fail($e->getMessage());
        }
    }

    public function cascadeDelete(Request $request)
    {
        Db::startTrans();
        try {
            $ingeniousEngine = $this->service;
            $id              = $request->param('id', '');
            $ingeniousEngine->processInstanceService()->cascadeDelete($id, $request->adminId());
            Db::commit();
            return app('json')->success('删除成功');
        } catch (LFlowException $e) {
            Db::rollback();
            return app('json')->fail($e->getMessage());
        }
    }

    public function highLightData(Request $request)
    {
        $ingeniousEngine = $this->service;
        $id              = $request->param('id');
        $result          = $ingeniousEngine->processInstanceService()->highLight($id);
        return app('json')->success($result);
    }

    public function approvalRecord(Request $request)
    {
        $ingeniousEngine = $this->service;
        $id              = $request->param('id');
        $result          = $ingeniousEngine->processInstanceService()->approvalRecord($id);
        return app('json')->success($result);
    }

    /*流程实例抄送******************************************************************************************************/
    public function ccList(Request $request)
    {
        $args             = $this->request->getMore([
            ['actor_id', $request->adminId()],
            ['name', ''],
            ['display_name', ''],
            ['business_no', ''],
            ['page', 0],
            ['limit', 0],
        ]);
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processInstanceService()->ccInstancePage((object)$args);
        return app('json')->success($result);
    }

}
