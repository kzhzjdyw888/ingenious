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

namespace app\adminapi\controller\v1\wf;

class ProcessUser implements \ingenious\interface\ProcessUser
{

    public function getRealName(int|string $userId): string
    {
        // TODO: Implement getRealName() method.
    }

    public function getDeptId(string $userId): string
    {
        // TODO: Implement getDeptId() method.
    }

    public function getDeptName(string $userId): string
    {
        // TODO: Implement getDeptName() method.
    }

    public function getPostId(string $userId): string
    {
        // TODO: Implement getPostId() method.
    }

    public function getPostName(string $userId): string
    {
        // TODO: Implement getPostName() method.
    }
}
