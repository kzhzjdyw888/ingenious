<?php

namespace app\adminapi\middleware;

use app\services\system\admin\SystemAuthServices;
use support\Container;
use Webman\Config;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 *
 * Token验证中间件
 * @author Mr.April
 * @since  1.0
 */
class AdminAuthTokenMiddleware implements MiddlewareInterface
{

    public function process(Request $request, callable $handler): Response
    {
        $token = trim(ltrim($request->header(Config('app.token_name', 'Authorization')), 'Bearer'));
        if (!$token) {
            $token = trim(ltrim($request->get('token')));
        }
        /** @var SystemAuthServices $service */
        $service   = Container::make(SystemAuthServices::class);
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

        return $handler($request);
    }
}
