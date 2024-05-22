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
use app\adminapi\controller\v1\wf\api\LoginUserHolder;
use app\adminapi\controller\v1\wf\api\UserProcessingApi;
use app\adminapi\controller\v1\wf\config\ConfigurationRewrite;
use app\Request;
use ingenious\core\ProcessEngines;
use ingenious\enums\ProcessConst;
use ingenious\ex\LFlowException;
use ingenious\interface\ProcessEnginesInterface;
use ingenious\libs\utils\ProcessFlowUtils;
use think\App;
use think\facade\Db;

//
class Define extends AuthController
{

    protected ProcessEnginesInterface $service;

    public function __construct(App $app, $config = [])
    {
        $request = \request();
        parent::__construct($app);
        $config = [
            'loginUserHolder' => new LoginUserHolder($request),
            'findUserApi'     => new UserProcessingApi(),
        ];

        $this->service = new ProcessEngines(new ConfigurationRewrite($config));
    }

    public function index(Request $request): \think\response\Json
    {
        $args             = $this->request->getMore([
            ['id', ''],
            ['type_id', ''],
            ['name', ''],
            ['display_name', ''],
            ['instance_url', ''],
            ['state', ''],//默认全部
            ['version', ''],
            ['creator', ''],
            ['page', 0],
            ['limit', 0],
        ]);
        $favoriteParam    = [
            'user_id'  => $request->adminId(),
            'favorite' => 1,
            'page'     => 0,
            'limit'    => 0,
        ];
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processDefineService()->page((object)$args);
        $favorite         = $ingeniousEngines->processDefineService()->favoritePage((object)$favoriteParam);
        $favoriteData     = isset($favorite['list']) && !empty($favorite['list']) ? $favorite['list'] : [];

        //匹配是否为收藏的流程
        foreach ($result['list'] as $key => $value) {
            foreach ($favoriteData as $val) {
                $result['list'][$key]['favorite']                   = 0;
                $result['list'][$key]['process_define_favorite_id'] = '';
                if ($value['id'] === $val['process_define_id']) {
                    $result['list'][$key]['favorite']                   = 1;
                    $result['list'][$key]['process_define_favorite_id'] = $val['id'];
                    continue;
                }
            }
        }
        return app('json')->success($result);
    }

    public function read(string $id): \think\Response\Json
    {
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processDefineService()->findById($id);
        if ($result != null) {
            return app('json')->success($result->toArray());
        } else {
            return app('json')->fail('参数错误');
        }
    }

    public function setStatus(Request $request)
    {
        try {
            $ingeniousEngines = $this->service;
            $id               = $request->param('id');
            $state            = $request->param('state');
            $ingeniousEngines->processDefineService()->updateState($id, $state, $request->adminId());
            return app('json')->success('操作成功');
        } catch (LFlowException $e) {
            return app('json')->fail($e->getMessage());
        }
    }

    public function startAndExecute(Request $request)
    {
        Db::startTrans();
        try {
            $args = ProcessFlowUtils::variableToDict($request->param());
            $args->put(ProcessConst::USER_USER_ID, $request->adminId());
            $processDefineId = $request->param(ProcessConst::PROCESS_DEFINE_ID_KEY);
            if (empty($processDefineId)) {
                return app('json')->fail(ProcessConst::PROCESS_DEFINE_ID_KEY + '必须参数不能为空');
            }
            $ingeniousEngines = $this->service;
            $ingeniousEngines->processInstanceService()->startAndExecute($processDefineId, $args);
            Db::commit();
            return app('json')->success('发起成功');
        } catch (LFlowException $e) {
            Db::rollback();
            return app('json')->fail($e->getMessage());
        }

    }

    public function delete(string $id): \think\Response\Json
    {
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processDefineService()->del($id);
        if (!$result) {
            return app('json')->fail('操作失败');
        }
        return app('json')->success('删除成功');
    }

    public function processFavorite(Request $request)
    {
        $ingeniousEngines = $this->service;
        $param            = $request->postMore([
            ['user_id', $request->adminId()],
            ['process_define_id'],
            ['favorite', 1],
        ]);
        $result           = $ingeniousEngines->processDefineService()->definitionFavorite((object)$param);
        if (!$result) {
            return app('json')->fail('收藏失败');
        }
        return app('json')->success('收藏成功');
    }

    public function favoritePage(Request $request): \think\Response\Json
    {
        $ingeniousEngines = $this->service;
        $param            = $request->postMore([
            ['user_id', $request->adminId()],
            ['process_define_id', ''],
            ['favorite', 1],
            ['page', 0],
            ['limit', 0],
        ]);
        $result           = $ingeniousEngines->processDefineService()->favoritePage((object)$param);
        return app('json')->success($result);
    }

    public function favoriteDelete(Request $request): \think\Response\Json
    {
        $ingeniousEngines = $this->service;
        $data             = $request->param('data', []);
        $result           = $ingeniousEngines->processDefineService()->favoriteDel($data);
        if (!$result) {
            return app('json')->fail('操作失败');
        }
        return app('json')->success('操作成功');
    }

}
