<?php
/**
 *+------------------
 * madong
 *+------------------
 * Copyright (c) https://gitcode.com/motion-code  All rights reserved.
 *+------------------
 * Author: Mr. April (405784684@qq.com)
 *+------------------
 * Official Website: https://madong.tech
 */

namespace app\admin\controller\wf;

use app\common\api\WorkflowAPI;
use app\common\util\Json;
use madong\ingenious\enums\ProcessConstEnum;
use think\App;
use think\facade\Request;

class Form extends \app\admin\controller\Base
{

    protected array $middleware = ['AdminCheck', 'AdminPermission'];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->service = new WorkflowAPI();
    }

    public function index(): string
    {
        return $this->fetch('wf/form/index');
    }

    /**
     * 获取列表
     *
     * @return mixed
     * @throws \Exception
     */
    public function select(): \think\Response
    {
        $param           = $this->request->getMore([
            ['id', ''],
            ['name', ''],
            ['display_name', ''],
        ]);
        $format          = input('format', 'normal');
        $methods         = [
            'select'     => 'formatSelect',
            'tree'       => 'formatTree',
            'table_tree' => 'formatTableTree',
            'normal'     => 'formatNormal',
        ];
        $format_function = $methods[$format] ?? 'formatNormal';
        $result          = $this->service->client('form.list', (object)$param);
        return call_user_func([$this, $format_function], $result['items'], $result['total']);
    }

    /**
     * 详情
     *
     * @return \think\Response
     */
    public function show(): \think\Response
    {
        try {
            $id     = input('get.id');
            $result = $this->service->client('form.findById', $id);
            return $result
                ? Json::success('ok', $result->toArray())
                : Json::fail('Resource not found');
        } catch (\Throwable $e) {
            return Json::fail($e->getMessage());
        }
    }

    public function getByName(): \think\Response
    {
        try {
            $name   = input('get.name');
            $result = $this->service->client('form.findByName', $name);
            return $result
                ? Json::success('ok', $result->toArray())
                : Json::fail('Resource not found');
        } catch (\Exception $e) {
            return Json::fail($e->getMessage());
        }
    }

    /**
     * 插入
     *
     * @return \think\Response|string
     */
    public function insert(): \think\Response|string
    {
        if (Request::method() === 'POST') {
            try {
                $data = $this->request->postMore([
                    ['name', ''],
                    ['display_name', ''],
                    ['description', ''],
                    ['type_id', ''],
                    ['icon', ''],
                    ['enabled', 1],
                    ['remark', ''],
                    ['create_user', getCurrentUser()],
                ]);
                if (empty($data['type_id'])) {
                    return Json::fail('请选择类型');
                }
                if (empty($data['name'])) {
                    return Json::fail('唯一编码不能为空');
                }
                if (empty($data['display_name'])) {
                    return Json::fail('显示名称不能为空');
                }

                $result = $this->service->client('form.created', (object)$data);
                return Json::success('ok', [$result->getData($result->getPk())]);
            } catch (\Exception $e) {
                return Json::fail($e->getMessage());
            }
        }
        return $this->fetch('wf/form/insert');
    }

    /**
     * 更新
     *
     * @return \think\Response|string
     */
    public function update(): \think\Response|string
    {
        if (Request::method() === 'POST') {
            try {
                $data   = $this->request->postMore([
                    ['id'],
                    ['name', ''],
                    ['display_name', ''],
                    ['description', ''],
                    ['type_id', ''],
                    ['icon', ''],
                    ['remark', ''],
                    ['create_user', getCurrentUser()],
                ]);
                $result = $this->service->client('form.updated', (object)$data);
                return $result
                    ? Json::success('更新成功')
                    : Json::fail('更新失败');
            } catch (\Exception $e) {
                return Json::fail($e->getMessage());
            }

        }
        return $this->fetch('wf/form/update');
    }

    /**
     * 删除
     *
     * @return \think\Response
     */
    public function delete(): \think\Response
    {
        try {
            $id   = input('id');
            $data = $id !== null && $id !== '0' ? $id : input('data', null);
            if ($data === null) {
                throw new \Exception('参数错误：缺少必要的参数（id 或 data）');
            }
            $result = $this->service->client('form.del', $data);
            return Json::success('ok', $result);
        } catch (\Throwable $e) {
            return Json::fail($e->getMessage());
        }
    }

    /**
     * 设计视图
     *
     * @param \think\facade\Request $request
     *
     * @return string
     */
    public function design(Request $request): string
    {

//        $id       = $request->get('id');
//        $services = new ProcessFormService();
//        $result   = $services->findById($id);
//        $data     = $result->getData('form') ?? [];
        return $this->fetch('wf/common/luminar/index', ['data' => []]);
    }

    /**
     * 设计预览
     *
     * @return string
     */
    public function design_preview(): string
    {
        return $this->fetch('wf/common/luminar/preview');
    }

    /***
     * 表单预览
     *
     * @return string
     */
    public function preview(): string
    {
        return $this->fetch('wf/form/preview');
    }

    /**
     * 更新表单设计
     *
     * @return \think\Response
     */
    public function updateForm(): \think\Response
    {
        try {
            $data = [
                ProcessConstEnum::CREATE_USER->value         => getCurrentUser(),
                ProcessConstEnum::PROCESS_FORM_ID_KEY->value => input('id'),
                ProcessConstEnum::PROCESS_FORM_KEY->value    => input('form_data', []),
            ];
            $this->service->client('form.updateDesign', (object)$data);
            return Json::success('ok');
        } catch (\Exception $e) {
            return Json::fail($e->getMessage());
        }

    }

    /**
     * 获取内置表单
     *
     * @return \think\Response
     */
    public function internalDocument(): \think\Response
    {
        $instanceUrl = input('instance_url', '');
        $data        = config('wf.form', []);
        $value       = [];
        foreach ($data as $item) {
            if ($item['value'] === $instanceUrl) {
                $value = $item;
            }
        }
        return Json::success($value);

    }

}
