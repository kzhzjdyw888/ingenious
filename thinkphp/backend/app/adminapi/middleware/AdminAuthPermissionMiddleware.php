<?php
declare (strict_types=1);

namespace app\adminapi\middleware;

use app\Request;
use app\services\system\admin\SystemRoleServices;
use phoenix\exceptions\AuthException;
use think\facade\Request as FacadeRequest;

class AdminAuthPermissionMiddleware
{
    /*
    use \app\common\traits\Base;

    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle(Request $request, \Closure $next)
    {
        if (!$request->adminId() || !$request->adminInfo()) {
            throw new AuthException(100100);
        }
        //非超级管理员进行权限验证
        if ($request->adminInfo()['level'] !== 0) {
            /** @var SystemRoleServices $systemRoleServices */
            $systemRoleServices = app()->make(SystemRoleServices::class);
            $systemRoleServices->verifyAuth($request);
        }
        return $next($request);
    }
}
