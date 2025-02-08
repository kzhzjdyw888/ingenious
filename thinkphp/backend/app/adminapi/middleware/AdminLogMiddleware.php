<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2020 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

namespace app\adminapi\middleware;

use app\Request;
use app\services\system\log\SystemLogServices;

/**
 * 日志中間件
 * Class AdminLogMiddleware
 *
 * @package app\adminapi\middleware
 */
class AdminLogMiddleware
{
    /**
     * @param Request  $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        $response = $next($request);
        try {
            /** @var SystemLogServices $services */
            $services = app()->make(SystemLogServices::class);
            $services->recordAdminLog($request->adminId(), $request->adminInfo()['real_name'], $request->adminInfo()['type']);
        } catch (\Throwable $e) {
            echo($e);
        }
        return $response;
    }

}
