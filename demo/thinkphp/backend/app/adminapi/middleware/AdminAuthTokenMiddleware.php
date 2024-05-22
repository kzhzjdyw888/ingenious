<?php
declare (strict_types=1);

namespace app\adminapi\middleware;

use app\Request;
use app\services\system\admin\SystemAuthServices;
use think\facade\Config;

//前置中间件：管理员鉴定
class AdminAuthTokenMiddleware
{
    /**
     * 处理请求
     */
    public function handle(Request $request, \Closure $next)
    {

        $token = trim(ltrim($request->header(Config::get('cookie.token_name', 'Authori-zation')), 'Bearer'));
        if (!$token) {
            $token = trim(ltrim($request->get('token')));
        }

        /** @var SystemAuthServices $service */
        $service   = app()->make(SystemAuthServices::class);
        $adminInfo = $service->parseToken($token);

        $request->macro('isAdminLogin', function () use (&$adminInfo) {
            return !is_null($adminInfo);
        });
        $request->macro('adminId', function () use (&$adminInfo) {
            return $adminInfo['id'] ?? '';
        });

        $request->macro('adminInfo', function () use (&$adminInfo) {
            return $adminInfo;
        });

        return $next($request);
    }
}
