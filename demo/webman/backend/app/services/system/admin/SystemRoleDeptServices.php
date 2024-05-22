<?php

namespace app\services\system\admin;

use app\common\CacheService;
use app\dao\system\admin\SystemRoleDeptDao;
use app\services\BaseServices;
use support\Container;

/**
 * 角色权限规则
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemRoleDeptServices extends BaseServices
{
    /**
     * 初始化
     * SystemMenusServices constructor.
     */
    public function __construct()
    {
        $this->dao = Container::make(SystemRoleDeptDao::class);
    }


    /**
     * 角色分配组织
     * @param array $data
     *
     * @return mixed
     * @throws \Exception
     */
    public function create(array $data)
    {
        return $this->transaction(function () use ($data) {

            $this->delete($data['role_id'], 'role_id');
            if (empty($data['checked_dept'])) {
                return true;
            }
            $list = [];
            foreach ($data['checked_dept'] as $key => $value) {
                $list[$key]['role_id'] = $data['role_id'];
                $list[$key]['dept_id'] = $value;
            }
            if ($this->dao->saveAll($list)) {
                CacheService::clear();
                return true;
            } else {
                throw new AdminException('添加失败');
            }
        });
    }
}
