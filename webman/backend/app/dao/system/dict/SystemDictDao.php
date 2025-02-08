<?php

namespace app\dao\system\dict;

use app\dao\BaseDao;
use app\model\system\dict\SystemDict;

/**
 * 系统日志
 * Class SystemLogDao
 *
 * @package app\dao\admin\log
 */
class SystemDictDao extends BaseDao
{

    /**
     * 设置模型
     *
     * @return string
     */
    protected function setModel(): string
    {
        return SystemDict::class;
    }

    /**
     * getName 根据名称查询字典
     *
     * @param $name
     *
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getName($name): mixed
    {
        return $this->getModel()->where('name', $name)->find();
    }
}
