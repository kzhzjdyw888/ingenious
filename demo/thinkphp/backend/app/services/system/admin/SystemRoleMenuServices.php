<?php

namespace app\services\system\admin;

use app\dao\system\admin\SystemRoleMenuDao;
use app\services\BaseServices;
use phoenix\exceptions\AdminException;
use phoenix\services\CacheService;

/**
 * 角色权限规则
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemRoleMenuServices extends BaseServices
{
    /**
     * 初始化
     * SystemMenusServices constructor.
     */
    public function __construct(SystemRoleMenuDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 添加角色规则
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Exception
     */
    public function create(array $data)
    {
        return $this->transaction(function () use ($data) {

            $this->delete($data['role_id'], 'role_id');
            if (empty($data['checked_menus'])) {
                return true;
            }
            $list = [];
            foreach ($data['checked_menus'] as $key => $value) {
                $list[$key]['role_id'] = $data['role_id'];
                $list[$key]['menu_id'] = $value;
            }
            if ($this->dao->saveAll($list)) {
                CacheService::clear();
                return true;
            } else {
                throw new AdminException(100022);
            }
        });
    }

    /**
     * 获取权限id
     *
     * @param array $rules 角色Id
     *
     * @return array
     */
    public function getRoleIds(array $rules): array
    {
        $result = $this->dao->getColumn([['role_id', 'IN', $rules]], 'menu_id');
        return array_unique($result);
    }
}
