<?php
/**
 *+------------------
 * Lflow
 *+------------------
 * Copyright (c) 2023~2030 gitee.com/liu_guan_qing All rights reserved.本版权不可删除，侵权必究
 *+------------------
 * Author: Mr.April(405784684@qq.com)
 *+------------------
 */

namespace app\adminapi\controller\wf\api;

use app\dao\system\admin\SystemAdminDao;
use app\services\system\admin\SystemAdminServices;
use ingenious\interface\ProcessUserInterface;
use ingenious\libs\utils\Dict;

class UserProcessingApi implements ProcessUserInterface
{

    public ?SystemAdminServices $services;

    public function __construct()
    {
        $this->services = new SystemAdminServices(new SystemAdminDao());
    }

    /**
     * 引擎用户API实现类
     *
     * @param string|int $id
     *
     * @return object|null
     */
    public function findUser(string|int $id): ?object
    {
        $result = $this->services->get(['id' => $id], ['id', 'account as user_name', 'real_name'], ['departments', 'positions']);
        if (empty($result)) {
            return null;
        }
        $deptList  = $result->getData('departments');
        $postList  = $result->getData('positions');
        $dept_id   = [];
        $dept_name = [];
        $post_id   = [];
        $post_name = [];
        if (!empty($deptList)) {
            foreach ($deptList as $dept) {
                $dept_name[] = $dept->getData('dept_name');
                $dept_id[]   = $dept->getData('dept_id');
            }
        }
        if (!empty($postList)) {
            foreach ($postList as $post) {
                $post_name[] = $post->getData('post_name');
                $post_id[]   = $post->getData('post_id');
            }
        }
        $result->set('dept_name', implode(',', $dept_name));
        $result->set('post_name', implode(',', $post_name));
        $result->set('dept_id', implode(',', $dept_id));
        $result->set('post_id', implode(',', $post_id));
        return (object)$result->hidden(['departments', 'positions'])->toArray();
    }
}
