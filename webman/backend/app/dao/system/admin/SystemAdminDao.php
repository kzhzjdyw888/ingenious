<?php

namespace app\dao\system\admin;

use app\dao\BaseDao;
use app\model\system\admin\SystemAdmin;

/**
 * Class SystemAdminDao
 *
 * @package app\dao\system\admin
 */
class SystemAdminDao extends BaseDao
{
    protected function setModel(): string
    {
        return SystemAdmin::class;
    }

    /**
     * 获取管理员列表
     *
     * @param array $where
     * @param int   $page
     * @param int   $limit
     *
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(array $where, int $page, int $limit): array
    {
        return $this->search($where)->page($page, $limit)->select()->toArray();
    }

    /**
     * 用管理员名查找管理员信息
     *
     * @param string $account
     *
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException|\ReflectionException
     */
    public function accountByAdmin(string $account): array|\think\Model|null
    {
        return $this->search(['account' => $account, 'delete_time' => null])->find();
    }

    /**
     * 当前账号是否可用
     *
     * ./
     * @param string $account
     * @param string $id
     *
     * @return int
     * @throws \ReflectionException
     * @throws \think\db\exception\DbException
     */
    public function isAccountUsable(string $account, string $id)
    {
        return $this->search(['account' => $account, 'delete_time' => null])->where('id', '<>', $id)->count();
    }

    /**
     * 获取adminid
     *
     * @param int $level
     *
     * @return array
     */
    public function getAdminIds(int $level)
    {
        return $this->getModel()->where('level', '>=', $level)->column('id', 'id');
    }

    /**
     * 获取低于等级的管理员名称和id
     *
     * @param string $field
     * @param int    $level
     *
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOrdAdmin(string $field = 'real_name,id', int $level = 0): array
    {
        return $this->getModel()->where('level', '>=', $level)->field($field)->select()->toArray();
    }

    /**
     * 条件获取管理员数据
     *
     * @param $where
     *
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getInfo($where): mixed
    {
        return $this->getModel()->where($where)->find();
    }

}
