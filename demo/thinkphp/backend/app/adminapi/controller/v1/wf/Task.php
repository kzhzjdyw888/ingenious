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
use app\adminapi\controller\v1\wf\api\AssignmentDirectManager;
use app\adminapi\controller\v1\wf\api\LoginUserHolder;
use app\adminapi\controller\v1\wf\api\UserProcessingApi;
use app\adminapi\controller\v1\wf\config\ConfigurationRewrite;
use app\dao\system\admin\SystemAdminDao;
use app\Request;
use app\services\system\admin\SystemAdminServices;
use ingenious\core\ProcessEngines;
use ingenious\enums\ProcessConst;
use ingenious\enums\ProcessSubmitTypeEnum;
use ingenious\ex\LFlowException;
use ingenious\libs\utils\ProcessFlowUtils;
use think\App;
use think\facade\Db;

class Task extends AuthController
{
    protected ProcessEngines $service;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $request       = \request();
        $config        = [
            'loginUserHolder'         => new LoginUserHolder($request),
            'findUserApi'             => new UserProcessingApi(),
        ];

        $this->service = new ProcessEngines(new ConfigurationRewrite($config));
    }

    public function doneList(Request $request): \think\Response\Json
    {
        $ingeniousEngine = $this->service;
        $param           = $this->request->getMore([
            ['actor_id', $request->adminId()],
            ['display_name', ''],
            ['process_define_display_name', ''],
            ['page', 0],
            ['limit', 0],
            ['business_no', ''],
        ]);
        $result          = $ingeniousEngine->processTaskService()->doneList((object)$param);
        return app('json')->success('获取成功', $result);
    }

    //我的代办
    public function todoList(Request $request): \think\Response\Json
    {
        $ingeniousEngine = $this->service;
        $where           = $this->request->getMore([
            ['actor_id', $request->adminId()],
            ['display_name', ''],
            ['business_no', ''],
        ]);
        $result          = $ingeniousEngine->processTaskService()->todoList((object)$where);
        return app('json')->success('获取成功', $result);
    }

    public function execute(Request $request): \think\Response\Json
    {
        Db::startTrans();
        try {
            $ingeniousEngine = $this->service;
            $scene           = $request->param(ProcessConst::SUBMIT_TYPE);
            $processTaskId   = $request->param(ProcessConst::PROCESS_TASK_ID_KEY);
            $args            = ProcessFlowUtils::variableToDict((object)$request->param());
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
            return app('json')->success('操作成功');
        } catch (LFlowException $e) {
            Db::rollback();
            return app('json')->fail($e->getMessage());
        }
    }

    public function jumpAbleTaskNameList(Request $request)
    {
        $processInstanceId = $request->param(ProcessConst::PROCESS_INSTANCE_ID_KEY);
        $ingeniousEngine   = $this->service;
        $result            = $ingeniousEngine->processTaskService()->jumpAbleTaskNameList($processInstanceId);
        return app('json')->success($result);
    }



    public function userList(Request $request)
    {
        $where               = $request->getMore([['name', '', '', 'account_like']]);
        $systemAdminServices = new SystemAdminServices(new SystemAdminDao());
        $result              = $systemAdminServices->getAdminList($where);
        return app('json')->success($result);
    }

    public function surrogate(Request $request)
    {
        try {
            $ingeniousEngine = $this->service;
            $processTaskId   = $request->param('process_task_id');
            $actors          = $request->param('actor_ids');
            $ingeniousEngine->processTaskService()->addTaskActor($processTaskId, $actors);
            return app('json')->success('操作成功');
        } catch (LFlowException $e) {
            return app('json')->fail($e->getMessage());
        }
    }

    public function detail(Request $request)
    {
        $ingeniousEngine = $this->service;
        $id              = $request->param('id');
        $result          = $ingeniousEngine->processTaskService()->findById($id);
        if ($result == null) {
            return app('json')->fail('实例不存在或被删除');
        }
        return app('json')->success($result->toArray());
    }

}
