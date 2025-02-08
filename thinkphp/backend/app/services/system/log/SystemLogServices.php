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
     *
     * @param SystemLogDao $dao
     */
    public function __construct(SystemLogDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 记录访问日志
     *
     * @param int    $adminId
     * @param string $adminName
     * @param string $type
     *
     * @return bool
     */
    public function recordAdminLog(string|int $adminId, string $adminName, string $type)
    {
        $request = app()->request;
        $module  = app('http')->getName();
        $rule    = trim(strtolower($request->rule()->getRule()));

        /** @var SystemMenusServices $service */
        $service = app()->make(SystemMenusServices::class);
        $data    = [
            'method'     => $module,
            'admin_id'   => $adminId,
            'add_time'   => time(),
            'admin_name' => $adminName,
            'path'       => $rule,
            'page'       => $service->getVisitName($rule) ?: '未知',
            'ip'         => $request->ip(),
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
