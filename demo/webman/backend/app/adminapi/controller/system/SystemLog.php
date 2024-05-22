<?php

namespace app\adminapi\controller\system;

use app\common\Json;
use app\services\system\admin\SystemAdminServices;
use app\adminapi\controller\AuthController;
use app\services\system\log\SystemLogServices;
use support\Container;
use support\Request;

/**
 * 管理员操作记录
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemLog extends AuthController
{
    /**
     * 构造方法
     * SystemLog constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->services = Container::make(SystemLogServices::class);
        $this->services->deleteLog();
    }

    /**
     * 显示操作记录
     */
    public function index(Request $request): \support\Response
    {
        $where = $this->request->getMore([
            ['pages', ''],
            ['path', ''],
            ['ip', ''],
            ['admin_id', ''],
            ['data', '', '', 'time'],
        ]);
        return Json::success($this->services->getLogList($where, (int)$this->adminInfo['level']));
    }

    public function search_admin(Request $request): \support\Response
    {
        $services = Container::make(SystemAdminServices::class);
        $info     = $services->getOrdAdmin('id,real_name', $this->adminInfo['level']);
        return Json::success(compact('info'));
    }

}

