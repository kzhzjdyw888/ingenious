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

namespace app\adminapi\controller;

use app\BaseController;

/**
 * 基类 所有控制器继承的类
 * Class AuthController
 *
 * @package app\adminapi\controller
 */
class AuthController extends BaseController
{
    /**
     * 当前登陆管理员信息
     *
     * @var
     */
    protected $adminInfo;

    /**
     * 当前登陆管理员ID
     *
     * @var
     */
    protected string $adminId;

    /**
     * 当前管理员权限
     *
     * @var array
     */
    protected array $auth = [];

    /**
     * 初始化
     */
    protected function initialize()
    {
        $this->adminId   = request()->adminId();
        $this->adminInfo = request()->adminInfo();
        $this->auth      = request()->adminInfo['rules'] ?? [];
    }
}
