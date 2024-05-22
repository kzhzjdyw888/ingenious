<?php

namespace app\services\system\admin;

use app\dao\system\admin\SystemRolePostDao;
use app\services\BaseServices;
use phoenix\exceptions\AdminException;
use phoenix\services\CacheService;

/**
 * 角色权限规则
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemRolePostServices extends BaseServices
{
    /**
     * 初始化
     * SystemMenusServices constructor.
     */
    public function __construct(SystemRolePostDao $dao)
    {
        $this->dao = $dao;
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
            if (empty($data['checked_post'])) {
                return true;
            }
            $list = [];
            foreach ($data['checked_post'] as $key => $value) {
                $list[$key]['role_id'] = $data['role_id'];
                $list[$key]['post_id'] = $value;
            }
            if ($this->dao->saveAll($list)) {
                CacheService::clear();
                return true;
            } else {
                throw new AdminException(100022);
            }
        });
    }
}
