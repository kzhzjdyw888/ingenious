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
use ingenious\enums\ProcessConst;
use ingenious\ex\LFlowException;
use ingenious\interface\ProcessEnginesInterface;
use support\Request;
use think\facade\Db;

class DesignerController extends AuthController
{

    protected ProcessEnginesInterface $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new ProcessEngines(new ConfigurationRewrite([]));
    }

    public function index(Request $request): \support\Response
    {
        $param            = $request->getMore([
            ['id', ''],
            ['model_key', ''],
            ['model_name', ''],
            ['model_group_id', ''],
        ]);
        $ingeniousEngines = $this->service;
        $result           = $ingeniousEngines->processDesignService()->page((object)$param);
        return Json::success($result);
    }

    public function read(Request $request): \support\Response
    {
        $ingeniousEngines = $this->service;
        $id               = $request->input('id');
        $result           = $ingeniousEngines->processDesignService()->findById($id);
        if ($result != null) {
            return Json::success($result->toArray());
        } else {
            return Json::fail('参数错误');
        }
    }

    public function save(Request $request): \support\Response
    {
        $ingeniousEngines = $this->service;
        $data             = $request->postMore([
            ['name', ''],
            ['display_name', ''],
            ['description', ''],
            ['type_id', ''],
            ['icon', ''],
            ['remark', ''],
            ['create_user', $request->adminId()],
        ]);
        if (empty($data['name'])) {
            return Json::fail('唯一编码不能为空');
        }
        if (empty($data['display_name'])) {
            return Json::fail('显示名称不能为空');
        }
        if ($ingeniousEngines->processDesignService()->create((object)$data)) {
            return Json::success('添加成功');
        } else {
            return Json::fail('添加失败');
        }
    }

    public function updateDefine(Request $request): \support\Response
    {
        $data                            = $request->all();
        $data[ProcessConst::CREATE_USER] = $request->adminId();//追加用户id
        $ingeniousEngines                = $this->service;
        $result                          = $ingeniousEngines->processDesignService()->updateDefine((object)$data);
        if ($result) {
            return Json::success('保存成功');
        } else {
            return Json::fail('保存失败');
        }
    }

    public function delete(Request $request): \support\Response
    {
        $id               = $request->input('id');
        $ingeniousEngines = $this->service;
        if (!($ingeniousEngines->processDesignService()->del($id))) {
            return Json::fail('数据不存在');
        }
        return Json::success('删除成功');
    }

    public function batchRemove(Request $request): \support\Response
    {
        $data             = $request->input('data');
        $ingeniousEngines = $this->service;
        $error            = [];
        foreach (explode(',', $data) as $key => $value) {
            $ret = $ingeniousEngines->processDesignService()->del($value);
            if (!$ret) {
                $error[] = $value . '删除失败';
            }
        }
        if (count($error) > 0) {
            return Json::fail(import(',', $error));
        }
        return Json::success('删除成功');
    }

    public function update(Request $request): \support\Response
    {
        $ingeniousEngines = $this->service;
        $id               = $request->input('id');
        $data             = $request->postMore([
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
            return Json::success('更新成功');
        } else {
            return Json::fail('更新失败');
        }
    }

    public function deploy(Request $request): \support\Response
    {
        Db::startTrans();
        try {
            $processDesignid  = $request->input('id');
            $ingeniousEngines = $this->service;
            $ingeniousEngines->processDesignService()->deploy($processDesignid, $request->adminId());
            Db::commit();
            return Json::success('部署成功');
        } catch (LFlowException $e) {
            Db::rollback();
            return Json::fail($e->getMessage());
        }

    }

    public function redeploy(Request $request): \support\Response
    {
        Db::startTrans();
        try {
            $processDesignid  = $request->input('id');
            $ingeniousEngines = $this->service;
            $ingeniousEngines->processDesignService()->redeploy($processDesignid, $request->adminId());
            Db::commit();
            return Json::success('重新部署成功');
        } catch (LFlowException $e) {
            Db::rollback();
            return Json::fail($e->getMessage());
        }

    }
}
