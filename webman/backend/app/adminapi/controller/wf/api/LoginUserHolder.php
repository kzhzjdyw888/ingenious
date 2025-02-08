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


use ingenious\interface\LoginUserHolderInterface;
use support\Request;

/**
 * 当前系统用户Api
 *
 * @author Mr.April
 * @since  1.0
 */
class LoginUserHolder implements LoginUserHolderInterface
{

    public ?Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getUserId(): string|int
    {
        return $this->request->adminId() ?? '0';
    }

    public function getUserName(): string
    {
        $userInfo = $this->request->adminInfo();
        return $userInfo['account'] ?? '';
    }

    public function getRealName(): string
    {
        $userInfo = $this->request->adminInfo();
        return $userInfo['real_name'] ?? '';
    }

}
