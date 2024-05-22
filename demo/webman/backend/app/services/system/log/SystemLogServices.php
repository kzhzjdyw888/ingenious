<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

namespace app\services\system\log;

use app\dao\system\log\SystemLogDao;
use app\services\BaseServices;
use app\services\system\admin\SystemMenusServices;
use support\Container;

/**
 * 系统日志
 * Class SystemLogServices
 *
 * @package app\services\system\log
 * @method deleteLog() 定期删除日志
 */
class SystemLogServices extends BaseServices
{

    /**
     * 构造方法
     * SystemLogServices constructor.
     */
    public function __construct()
    {
        $this->dao = Container::make(SystemLogDao::class);
    }

    /**
     * 记录访问日志
     *
     * @param string|int $adminId
     * @param string     $adminName
     * @param string     $type
     *
     * @return bool
     */
    public function recordAdminLog(string|int $adminId, string $adminName, string $type): bool
    {
        $request = request();
        $isPath  = strpos($request->path(), $request->app);
        $path    = $request->path();
        if ($isPath !== false) {
            $path = substr($request->path(), $isPath + strlen($request->app) + 1);
            $path = ltrim($path, '/');
        }
        /** @var SystemMenusServices $service */
        $service = Container::make(SystemMenusServices::class);
        $data    = [
            'method'     => $request->app,
            'admin_id'   => $adminId??'',
            'add_time'   => time(),
            'admin_name' => $adminName,
            'path'       => $request->path(),
            'page'       => $service->getVisitName($path) ?: '未知',
            'ip'         => $request->getRemoteIp(),
            'type'       => $type,
        ];
        if ($this->dao->save($data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取系统日志列表
     *
     * @param array $where
     * @param int   $level
     *
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLogList(array $where, int $level): array
    {
        [$page, $limit] = $this->getPageValue();
        $list  = $this->dao->getLogList($where, $page, $limit);
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }
}
