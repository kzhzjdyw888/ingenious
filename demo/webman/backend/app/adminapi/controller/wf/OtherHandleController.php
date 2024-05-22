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
use app\adminapi\controller\wf\api\AssignmentDirectManager;
use app\adminapi\controller\wf\api\LoginUserHolder;
use app\adminapi\controller\wf\api\UserProcessingApi;
use app\adminapi\controller\wf\config\ConfigurationRewrite;
use app\common\Json;
use app\dao\system\admin\SystemAdminDao;
use app\services\system\admin\SystemAdminServices;
use ingenious\core\ProcessEngines;
use ingenious\libs\utils\ProcessFlowUtils;
use support\Container;
use support\Request;

class OtherHandleController extends AuthController
{
    protected ProcessEngines $service;

    public function __construct()
    {
        parent::__construct();
        $request = \request();
        $config  = [
            'loginUserHolder' => new LoginUserHolder($request),
            'findUserApi'     => new UserProcessingApi(),
        ];

        $this->service = new ProcessEngines(new ConfigurationRewrite($config));
    }

    public function candidatePage(Request $request): \support\Response
    {
        $ingeniousEngine = $this->service;
        $params          = $request->getMore(['process_task_id', 'limit', 'page']);
        $result          = $ingeniousEngine->processTaskService()->candidatePage(ProcessFlowUtils::variableToDict($params));
        return Json::success($result);
    }

    public function carbonPage(Request $request): \support\Response
    {
        $where           = $request->getMore([['search', '']]);
        $page            = $request->input('page', 0);
        $limit           = $request->input('limit', 0);
        $result          = [];
        $systemAdmin     = new SystemAdminServices(new SystemAdminDao());
        $result['list']  = $systemAdmin->selectList($where, '*', $page, $limit, '', [], true)->toArray();
        $result['count'] = $systemAdmin->count($where);
        return Json::success($result);
    }

    public function userList(Request $request): \support\Response
    {
        $param               = $request->input('search', '');
        $systemAdminServices = Container::make(SystemAdminServices::class);
        $result              = $systemAdminServices->getAdministratorsList(['account_like' => $param]);
        $data                = [];
        foreach ($result['list'] as $key => $value) {
            $data['list'][$key]['name']  = $value['real_name'];
            $data['list'][$key]['value'] = $value['id'];
        }
        $data['count'] = $result['count'];
        return Json::success($data);
    }

    public function userInfo(Request $request): \support\Response
    {
        $param = $request->input('search', '');
        if (empty($param)) {
            return Json::success([]);
        }
        $systemAdminServices = Container::make(SystemAdminServices::class);
        $result              = $systemAdminServices->getAdminList(['id_in' => $param]);
        $data                = [];
        foreach ($result['list'] as $key => $value) {
            $data['list'][$key]['name']  = $value['real_name'];
            $data['list'][$key]['value'] = $value['id'];
        }
        $data['count'] = $result['count'];
        return Json::success($data);
    }

    /**
     * 提供设计器对应的参与类
     *
     * @return array[]
     */
    public function assignmentHandler(): array
    {
        return [
            [
                'label' => '直属主管',//注册类的对应名称
                'value' => AssignmentDirectManager::class,
            ],
        ];
    }

    /**
     * 模拟表单列表 vue 获取html  可以根据自己来操作
     *
     * @return \support\Response
     */
    public function formHandler(): \support\Response
    {
        $data = [
            [
                'label' => '请假申请单',
                'value' => 'leaveForm.html',
            ],
            [
                'label' => '补卡申请单',
                'value' => 'reissueForm.html',
            ],
            [
                'label' => '测试申请单',
                'value' => 'test.html',
            ],
        ];
        return Json::success($data);
    }
}
