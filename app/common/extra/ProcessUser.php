<?php

namespace app\common\extra;

use app\common\model\AdminAdmin as M;
use madong\ingenious\interface\IProcessUser;

/**
 * 引擎用户搜索服务
 *
 * @author Mr.April
 * @since  1.0
 */
class ProcessUser implements IProcessUser
{

    public ?M $model;

    public function __construct()
    {
        $this->model = new M();
    }

    /**
     * 引擎用户API实现类
     *
     * @param string|int $id
     *
     * @return object|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function findUser(string|int $id): ?object
    {
        $result = $this->model->field('username as user_name,nickname as real_name')
            ->where('id', $id)
            ->find();

        if (empty($result)) {
            return null;
        }

        // admin没有内置部门职位预留空供扩展使用
        $result->department_name = ''; // 添加部门名称
        $result->position_name   = '';   // 添加职位名称
        return (object)$result->toArray(); // 转换为对象并返回
    }

}
