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

use app\adminapi\controller\wf\api\LoginUserHolder;
use app\adminapi\controller\wf\api\UserProcessingApi;
use app\adminapi\controller\wf\config\ConfigurationRewrite;
use app\common\Json;
use app\dao\system\admin\SystemAdminDao;
use app\services\system\admin\SystemAdminServices;
use ingenious\core\ProcessEngines;
use ingenious\enums\ProcessConst;
use ingenious\enums\ProcessSubmitTypeEnum;
use ingenious\ex\LFlowException;
use ingenious\libs\utils\ProcessFlowUtils;
use support\Request;
use think\facade\Db;

class TaskController extends AuthController
{
    protected ProcessEngines $service;

    public function __construct()
    {
        parent::__construct();
        $request       = request();
        $config        = [
            'loginUserHolder'         => new LoginUserHolder($request),
            'findUserApi'             => new UserProcessingApi(),
        ];

        $this->service = new ProcessEngines(new ConfigurationRewrite($config));
    }

    public function doneList(Request $request): \support\Response
    {
        $ingeniousEngine = $this->service;
        $param           = $request->getMore([
            ['actor_id', $request->adminId()],
            ['display_name', ''],
            ['process_define_display_name', ''],
            ['page', 0],
            ['limit', 0],
            ['business_no', ''],
        ]);
        $result          = $ingeniousEngine->processTaskService()->doneList((object)$param);
        return Json::success('获取成功', $result);
    }

    //我的代办
    public function todoList(Request $request): \support\Response
    {
        $ingeniousEngine = $this->service;
        $where           = $request->getMore([
            ['actor_id', $request->adminId()],
            ['display_name', ''],
            ['business_no', ''],
        ]);
        $result          = $ingeniousEngine->processTaskService()->todoList((object)$where);
        return Json::success('获取成功', $result);
    }

    public function execute(Request $request): \support\Response
    {
        Db::startTrans();
        try {
            $ingeniousEngine = $this->service;
            $scene           = $request->input(ProcessConst::SUBMIT_TYPE);
            $processTaskId   = $request->input(ProcessConst::PROCESS_TASK_ID_KEY);
            $args            = ProcessFlowUtils::variableToDict((object)$request->input());
            $operator        = $request->adminId();
            switch ($scene) {
                case ProcessSubmitTypeEnum::AGREE[0]:
                    //同意申请
                    $ingeniousEngine->executeProcessTask($processTaskId, $operator, $args);
                    break;
                case ProcessSubmitTypeEnum::REJECT[0]:
                    //拒绝申请
                    $ingeniousEngine->executeAndJumpToEnd($processTaskId, $operator, $args);
                    break;
                case ProcessSubmitTypeEnum::ROLLBACK[0]:
                    //退回上一步
                    $ingeniousEngine->executeAndJumpTask($processTaskId, $operator, $args);
                    break;
                case ProcessSubmitTypeEnum::JUMP[0]:
                    // 跳转到指定节点
                    $taskName = $args->get(ProcessConst::TASK_NAME);
                    $ingeniousEngine->executeAndJumpTask($processTaskId, $operator, $args, $taskName);
                    break;
                case ProcessSubmitTypeEnum::ROLLBACK_TO_OPERATOR[0]:
                    //退回申请人
                    $ingeniousEngine->executeAndJumpToFirstTaskNode($processTaskId, $operator, $args);
                    break;
                case ProcessSubmitTypeEnum::COUNTERSIGN_DISAGREE[0]:
                    //会签不同意 追加不同意标识
                    $args->put(ProcessConst::COUNTERSIGN_DISAGREE_FLAG, 1);
                    $ingeniousEngine->executeProcessTask($processTaskId, $operator, $args);
                    break;
                default:
                    //默认执行
                    $ingeniousEngine->executeProcessTask($processTaskId, $operator, $args);
                    break;
            }

            // 存在抄送
            $ccUserIds = $args->get(ProcessConst::CC_ACTORS);
            if (!empty($ccUserIds)) {
                $processInstanceId = $args->get(ProcessConst::PROCESS_INSTANCE_ID_KEY);
                if (is_array($ccUserIds)) {
                    $ccUserIds = implode(',', $ccUserIds);
                }
                //创建抄送列表
                $ingeniousEngine->processInstanceService()->createCCInstance($processInstanceId, $operator, $ccUserIds);
            }
            Db::commit();
            return Json::success('操作成功');
        } catch (LFlowException $e) {
            Db::rollback();
            return Json::fail($e->getMessage());
        }
    }

    public function jumpAbleTaskNameList(Request $request): \support\Response
    {
        $processInstanceId = $request->input(ProcessConst::PROCESS_INSTANCE_ID_KEY);
        $ingeniousEngine   = $this->service;
        $result            = $ingeniousEngine->processTaskService()->jumpAbleTaskNameList($processInstanceId);
        return Json::success($result);
    }



    public function userList(Request $request): \support\Response
    {
        $where               = $request->getMore([['name', '', '', 'account_like']]);
        $systemAdminServices = new SystemAdminServices(new SystemAdminDao());
        $result              = $systemAdminServices->getAdminList($where);
        return Json::success($result);
    }

    public function surrogate(Request $request): \support\Response
    {
        try {
            $ingeniousEngine = $this->service;
            $processTaskId   = $request->input('process_task_id');
            $actors          = $request->input('actor_ids');
            $ingeniousEngine->processTaskService()->addTaskActor($processTaskId, $actors);
            return Json::success('操作成功');
        } catch (LFlowException $e) {
            return Json::fail($e->getMessage());
        }
    }

    public function detail(Request $request): \support\Response
    {
        $ingeniousEngine = $this->service;
        $id              = $request->input('id');
        $result          = $ingeniousEngine->processTaskService()->findById($id);
        if ($result == null) {
            return Json::fail('实例不存在或被删除');
        }
        return Json::success($result->toArray());
    }

}
