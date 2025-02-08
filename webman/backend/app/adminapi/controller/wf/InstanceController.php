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
use Exception;
use ingenious\core\ProcessEngines;
use ingenious\ex\LFlowException;
use ingenious\interface\ProcessEnginesInterface;
use support\Request;
use think\facade\Db;

class InstanceController extends AuthController
{

    protected ProcessEnginesInterface $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new ProcessEngines(new ConfigurationRewrite([]));
    }

    public function index(Request $request): \support\Response
    {
        $args             = $request->getMore([
            ['operator', $request->adminId()],
            ['business_no', ''],
            ['display_name', ''],
            ['page', 0],
            ['limit', 0],
        ]);
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processInstanceService()->page((object)$args);
        return Json::success($result);
    }

    public function management(Request $request): \support\Response
    {
        $args             = $request->getMore([
            ['business_no', ''],
            ['display_name', ''],
            ['page', 0],
            ['limit', 0],
        ]);
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processInstanceService()->page((object)$args);
        return Json::success($result);
    }

    public function detail(Request $request): \support\Response
    {

        $ingeniousEngine = $this->service;
        $id              = $request->input('id');
        $result          = $ingeniousEngine->processInstanceService()->findById($id);
        if ($result == null) {
            return Json::fail('实例不存在或被删除');
        }
        return Json::success($result->toArray());
    }

    public function withdraw(Request $request): \support\Response
    {
        Db::startTrans();
        try {
            $ingeniousEngine = $this->service;
            $id              = $request->input('id', '');
            foreach (explode(',', $id) as $value) {
                $ingeniousEngine->processInstanceService()->withdraw($value, $request->adminId());
            }
            Db::commit();
            return Json::success('撤回成功');
        } catch (LFlowException $e) {
            Db::rollback();
            return Json::fail($e->getMessage());
        }
    }

    public function cascadeDelete(Request $request): \support\Response
    {
        Db::startTrans();
        try {
            $ingeniousEngine = $this->service;
            $id              = $request->input('id', '');
            $ingeniousEngine->processInstanceService()->cascadeDelete($id, $request->adminId());
            Db::commit();
            return Json::success('删除成功');
        } catch (LFlowException $e) {
            Db::rollback();
            return Json::fail($e->getMessage());
        }
    }

    public function highLightData(Request $request): \support\Response
    {
        $ingeniousEngine = $this->service;
        $id              = $request->input('id');
        try {
        $result          = $ingeniousEngine->processInstanceService()->highLight($id);

        }catch (LFlowException $e){
            var_dump($e->getMessage());
        }
        return Json::success($result);
    }

    public function approvalRecord(Request $request): \support\Response
    {
        $ingeniousEngine = $this->service;
        $id              = $request->input('id');
        $result          = $ingeniousEngine->processInstanceService()->approvalRecord($id);
        return Json::success($result);
    }

    /*流程实例抄送******************************************************************************************************/
    public function ccList(Request $request): \support\Response
    {
        $args             = $request->getMore([
            ['actor_id', $request->adminId()],
            ['name', ''],
            ['display_name', ''],
            ['business_no', ''],
            ['page', 0],
            ['limit', 0],
        ]);
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processInstanceService()->ccInstancePage((object)$args);
        return Json::success($result);
    }

}
