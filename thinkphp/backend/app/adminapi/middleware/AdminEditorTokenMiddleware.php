<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

namespace app\adminapi\middleware;


use app\Request;
use phoenix\interfaces\MiddlewareInterface;
use think\facade\Config;

/**
 * 后台登陆验证中间件
 * Class AdminAuthTokenMiddleware
 * @package app\adminapi\middleware
 */
class AdminEditorTokenMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next)
    {
        $token = CacheService::get(trim($request->get('fileToken')));

        /** @var SystemFileServices $service */
        $service = app()->make(SystemFileServices::class);
        $service->parseToken($token);

        return $next($request);
    }
}
