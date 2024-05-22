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
use ingenious\libs\utils\ProcessFlowUtils;
use think\App;

class OtherHandle extends AuthController
{
    protected ProcessEngines $service;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $request = \request();
        $config  = [
            'loginUserHolder' => new LoginUserHolder($request),
            'findUserApi'     => new UserProcessingApi(),
        ];

        $this->service = new ProcessEngines(new ConfigurationRewrite($config));
    }

    public function candidatePage(Request $request)
    {
        $ingeniousEngine = $this->service;
        $params          = $request->getMore(['process_task_id', 'limit', 'page']);
        $result          = $ingeniousEngine->processTaskService()->candidatePage(ProcessFlowUtils::variableToDict($params));
        return app('json')->success($result);
    }

    public function carbonPage(Request $request)
    {
        $where           = $request->getMore([['search', '']]);
        $page            = $request->param('page', 0);
        $limit           = $request->param('limit', 0);
        $result          = [];
        $systemAdmin     = new SystemAdminServices(new SystemAdminDao());
        $result['list']  = $systemAdmin->selectList($where, '*', $page, $limit, '', [], true)->toArray();
        $result['count'] = $systemAdmin->count($where);
        return app('json')->success($result);
    }

    public function userList(Request $request)
    {
        $param               = $this->request->param('search', '');
        $systemAdminServices = app()->make(SystemAdminServices::class);
        $result              = $systemAdminServices->getAdministratorsList(['account_like' => $param]);
        $data                = [];
        foreach ($result['list'] as $key => $value) {
            $data['list'][$key]['name']  = $value['real_name'];
            $data['list'][$key]['value'] = $value['id'];
        }
        $data['count'] = $result['count'];
        return app('json')->success($data);
    }

    public function userInfo(Request $request)
    {
        $param = $this->request->param('search', '');
        if (empty($param)) {
            return app('json')->success([]);
        }
        $systemAdminServices = app()->make(SystemAdminServices::class);
        $result              = $systemAdminServices->getAdminList(['id_in' => $param]);
        $data                = [];
        foreach ($result['list'] as $key => $value) {
            $data['list'][$key]['name']  = $value['real_name'];
            $data['list'][$key]['value'] = $value['id'];
        }
        $data['count'] = $result['count'];
        return app('json')->success($data);
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
     * @return \think\Response
     */
    public function formHandler(): \think\Response
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
        return app('json')->success($data);
    }
}
