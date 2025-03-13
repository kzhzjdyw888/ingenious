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
use think\App;
use think\facade\Request;

class Category extends \app\admin\controller\Base
{
    /**
     * @var array|string[]
     */
    protected array $middleware = ['AdminCheck', 'AdminPermission'];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->service = new WorkflowAPI();
    }

    public function index(): string
    {
        return $this->fetch();
    }

    public function select(): \think\Response
    {
        $param           = $this->request->getMore([
            ['status', ''],
            ['name', ''],
            ['is_del', 0],
            ['start_time'],
            ['end_time'],
            ['page', 1],
            ['limit', 10],
        ]);
        $format          = input('format', 'normal');
        $methods         = [
            'select'     => 'formatSelect',
            'tree'       => 'formatTree',
            'table_tree' => 'formatTableTree',
            'normal'     => 'formatNormal',
        ];
        $format_function = $methods[$format] ?? 'formatNormal';
        $result          = $this->service->client('category.list', (object)$this->request->param());
        return call_user_func([$this, $format_function], $result['items'], $result['total']);

    }

    /**
     * 插入
     *
     * @return string|\think\Response
     */
    public function insert(): string|\think\Response
    {
        if ($this->request->method() === 'POST') {
            try {
                $data   = $this->request->postMore([
                    ['pid', 0],
                    ['name', ''],
                    ['icon', ''],
                    ['remark', ''],
                    ['sort', 10],
                    ['create_user', getCurrentUser()],
                ]);
                $result = $this->service->client('category.created', (object)$data);
                return Json::success('ok', [$result->getData($result->getPk())]);
            } catch (\Exception $e) {
                return Json::fail($e->getMessage());
            }
        }
        return $this->fetch('wf/category/insert');
    }

    /**
     * 详情
     *
     * @return \think\Response
     * @throws \Exception
     */
    public function read(): \think\Response
    {
        $id     = input('get.id');
        $result = $this->service->client('category.findById', $id);
        return $result
            ? Json::success('ok', $result->toArray())
            : Json::fail('Not Found');

    }

    /**
     * 更新
     *
     * @return string|\think\Response
     */
    public function update(): string|\think\Response
    {
        if (Request::method() === 'POST') {
            try {
                $data   = $this->postMore([
                    ['id'],
                    ['name', ''],
                    ['icon', ''],
                    ['pid', '-1'],
                    ['remark', ''],
                    ['sort', 10],
                    ['user_id', getCurrentUser()],
                ]);
                $result = $this->service->client('category.updated', (object)$data);
                return $result
                    ? Json::success('更新成功')
                    : Json::fail('更新失败');
            } catch (\Exception $e) {
                return Json::fail($e->getMessage());
            }
        }
        return $this->fetch('wf/category/update');
    }

    /**
     * 删除
     *
     * @param \think\facade\Request $request
     *
     * @return \think\Response
     */
    public function delete(Request $request): \think\Response
    {

        try {
            $id   = input('id');
            $data = (array)input('data', []);
            $data = !empty($id) && $id !== '0' ? $id : $data;
            if (empty($data)) {
                throw new \Exception('参数错误');
            }
            $result = $this->service->client('category.del', $data);
            return Json::success('ok', $result);
        } catch (\Throwable $e) {
            return Json::fail($e->getMessage());
        }
    }

}
