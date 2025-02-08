<?php

namespace app\adminapi\middleware;

use app\exception\AuthException;
use app\services\system\admin\SystemRoleServices;
use support\Container;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 *
 * 权限中间件
 * @author Mr.April
 * @since  1.0
 */
class AdminAuthPermissionMiddleware implements MiddlewareInterface
{

    /**
     * @throws \app\exception\AuthException
     */
    public function process(Request $request, callable $handler): Response
    {
        if (!$request->adminId() || !$request->adminInfo()) {
            throw new AuthException('参数错误');
        }
        //非超级管理员进行权限验证
        if ($request->adminInfo()['level'] !== 0) {
            /** @var SystemRoleServices $services */
            $services = Container::make(SystemRoleServices::class);
            $services->verifyAuth($request);
        }
        return $handler($request);
    }
}
