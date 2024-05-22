<?php

namespace app\adminapi\middleware;

use app\services\system\log\SystemLogServices;
use support\Container;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 *
 * 日志中间件
 * @author Mr.April
 * @since  1.0
 */
class AdminLogMiddleware implements MiddlewareInterface
{

    public function process(Request $request, callable $handler): Response
    {
        $response = $handler($request);

        try {
            /** @var SystemLogServices $services */
            $services = Container::make(SystemLogServices::class);
            $services->recordAdminLog($request->adminId(), $request->adminInfo()['real_name'], $request->adminInfo()['type']);
        } catch (\Exception $e) {
            echo($e);
        }
        return $response;
    }
}
