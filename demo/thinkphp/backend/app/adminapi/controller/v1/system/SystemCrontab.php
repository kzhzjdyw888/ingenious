<?php

namespace app\adminapi\controller\v1\system;

use app\adminapi\controller\AuthController;
use app\services\system\crontab\SystemCrontabServices;
use think\App;

/**
 *
 * 定时任务
 * @author Mr.April
 * @since  1.0
 */
class SystemCrontab extends AuthController
{
    public function __construct(App $app, SystemCrontabServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 获取定时任务列表
     *
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException|\ReflectionException
     */
    public function getTimerList(): \think\response\Json
    {

        $where = ['delete_time' => null];
        return app('json')->success($this->services->getTimerList($where));
    }

    /**
     * 获取定时任务详情
     *
     * @param $id
     *
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getTimerInfo($id): \think\response\Json
    {
        return app('json')->success($this->services->getTimerInfo($id));
    }

    /**
     * 获取定时任务类型
     *
     * @return mixed
     */
    public function getMarkList(): \think\response\Json
    {
        return app('json')->success($this->services->getMarkList());
    }

    /**
     * 保存更新定时任务
     *
     * @return mixed
     */
    public function saveTimer(): \think\response\Json
    {
        $data = $this->request->postMore([
            ['id', 0],
            ['name', ''],
            ['mark', ''],
            ['content', ''],
            ['type', 0],
            ['is_open', 0],
            ['week', 0],
            ['day', 0],
            ['hour', 0],
            ['minute', 0],
            ['second', 0],
        ]);
        $this->services->saveTimer($data);
        return app('json')->success(100000);
    }

    /**
     * 删除定时任务
     *
     * @param $id
     *
     * @return \think\response\Json
     */
    public function delTimer($id): \think\response\Json
    {
        $this->services->delTimer($id);
        return app('json')->success(100002);
    }

    /**
     * 设置定时任务状态
     *
     * @param $id
     * @param $is_open
     *
     * @return mixed
     */
    public function setTimerStatus($id, $is_open): \think\response\Json
    {
        $this->services->setTimerStatus($id, $is_open);
        return app('json')->success(100014);
    }

}
