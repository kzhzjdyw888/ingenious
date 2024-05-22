<?php

namespace app\adminapi\controller\wf;

use app\adminapi\controller\AuthController;
use app\adminapi\controller\wf\api\LoginUserHolder;
use app\adminapi\controller\wf\api\UserProcessingApi;
use app\adminapi\controller\wf\config\ConfigurationRewrite;
use app\common\Json;
use ingenious\core\ProcessEngines;
use ingenious\enums\ProcessConst;
use ingenious\ex\LFlowException;
use ingenious\interface\ProcessEnginesInterface;
use ingenious\libs\utils\ProcessFlowUtils;
use support\Request;
use think\facade\Db;

class DefineController extends AuthController
{

    protected ProcessEnginesInterface $service;

    public function __construct()
    {
        parent::__construct();
        $config = [
            'loginUserHolder' => new LoginUserHolder(request()),
            'findUserApi'     => new UserProcessingApi(),
        ];

        $this->service = new ProcessEngines(new ConfigurationRewrite($config));
    }

    public function index(Request $request): \support\Response
    {
        $args             = $request->getMore([
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
        return Json::success($result);
    }

    public function read(Request $request): \support\Response
    {
        $id=$request->input('id');
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processDefineService()->findById($id);
        if ($result != null) {
            return Json::success($result->toArray());
        } else {
            return Json::fail('参数错误');
        }
    }

    public function setStatus(Request $request): \support\Response
    {
        try {
            $ingeniousEngines = $this->service;
            $id               = request()->input('id');
            $state            = request()->input('state');
            $ingeniousEngines->processDefineService()->updateState($id, $state, $request->adminId());
            return Json::success('操作成功');
        } catch (LFlowException $e) {
            return Json::fail($e->getMessage());
        }
    }

    public function startAndExecute(Request $request): \support\Response
    {
        Db::startTrans();
        try {
            $args = ProcessFlowUtils::variableToDict($request->all());
            $args->put(ProcessConst::USER_USER_ID, $request->adminId());
            $processDefineId = request()->input(ProcessConst::PROCESS_DEFINE_ID_KEY);
            if (empty($processDefineId)) {
                return Json::fail(ProcessConst::PROCESS_DEFINE_ID_KEY + '必须参数不能为空');
            }
            $ingeniousEngines = $this->service;
            $ingeniousEngines->processInstanceService()->startAndExecute($processDefineId, $args);
            Db::commit();
            return Json::success('发起成功');
        } catch (LFlowException $e) {
            Db::rollback();
            return Json::fail($e->getMessage());
        }

    }

    public function delete(Request $request): \support\Response
    {
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processDefineService()->del($request->input('id'));
        if (!$result) {
            return Json::fail('操作失败');
        }
        return Json::success('删除成功');
    }

    public function processFavorite(Request $request): \support\Response
    {
        $ingeniousEngines = $this->service;
        $param            = $request->postMore([
            ['user_id', $request->adminId()],
            ['process_define_id'],
            ['favorite', 1],
        ]);
        $result           = $ingeniousEngines->processDefineService()->definitionFavorite((object)$param);
        if (!$result) {
            return Json::fail('收藏失败');
        }
        return Json::success('收藏成功');
    }

    public function favoritePage(Request $request): \support\Response
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
        return Json::success($result);
    }

    public function favoriteDelete(Request $request): \support\Response
    {
        $ingeniousEngines = $this->service;
        $data             = request()->input('data', []);
        $result           = $ingeniousEngines->processDefineService()->favoriteDel($data);
        if (!$result) {
            return Json::fail('操作失败');
        }
        return Json::success('操作成功');
    }

}
