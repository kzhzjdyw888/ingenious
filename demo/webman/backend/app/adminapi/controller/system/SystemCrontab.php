<?php

namespace app\adminapi\controller\system;

use app\adminapi\controller\AuthController;
use app\common\Json;
use app\services\system\crontab\SystemCrontabServices;
use support\Container;
use support\Request;

/**
 * 定时任务
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemCrontab extends AuthController
{
    public function __construct()
    {
        parent::__construct();
        $this->services = Container::make(SystemCrontabServices::class);
    }

    /**
     * 获取定时任务列表
     *
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException|\ReflectionException
     */
    public function getTimerList(Request $request): \support\Response
    {

        $where = ['delete_time' => null];
        return Json::success($this->services->getTimerList($where));
    }

    /**
     * 获取定时任务详情
     *
     * @param \support\Request $request
     *
     * @return mixed
     */
    public function getTimerInfo(Request $request): \support\Response
    {
        $id = $request->input('id');
        return Json::success($this->services->getTimerInfo($id));
    }

    /**
     * 获取定时任务类型
     *
     * @return mixed
     */
    public function getMarkList(Request $request): \support\Response
    {
        return Json::success($this->services->getMarkList());
    }

    /**
     * 保存更新定时任务
     *
     * @return mixed
     */
    public function saveTimer(Request $request): \support\Response
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
        return Json::success(100000);
    }

    /**
     * 删除定时任务
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function delTimer(Request $request): \support\Response
    {
        $id = $request->input('id');
        $this->services->delTimer($id);
        return Json::success(100002);
    }

    /**
     * 设置定时任务状态
     *
     * @param \support\Request $request
     *
     * @return mixed
     */
    public function setTimerStatus(Request $request): \support\Response
    {
        $id      = $request->input('id');
        $is_open = $request->input('is_open');
        $this->services->setTimerStatus($id, $is_open);
        return Json::success(100014);
    }
}
