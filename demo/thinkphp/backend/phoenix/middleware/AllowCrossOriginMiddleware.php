<?php
declare (strict_types=1);

namespace phoenix\middleware;

use app\Request;
use phoenix\interfaces\MiddlewareInterface;
use think\facade\Config;
use think\Response;

class AllowCrossOriginMiddleware implements MiddlewareInterface
{

    /**
     * 允许跨域的域名
     *
     * @var string
     */
    protected string $cookieDomain;

    /**
     * 处理请求
     *
     * @param \app\Request $request
     * @param \Closure     $next
     *
     * @return mixed|void
     */
    public function handle(Request $request, \Closure $next)   //在某些需求下，可以使用第三个参数传入额外的参数。
    {
        $this->cookieDomain = Config::get('cookie.domain', '');
        $header             = Config::get('cookie.header');
        $origin             = $request->header('origin');
        if ($origin && ('' == $this->cookieDomain || strpos($origin, $this->cookieDomain)))
            $header['Access-Control-Allow-Origin'] = $origin;
        if ($request->method(true) == 'OPTIONS') {
            $response = Response::create('ok')->code(200)->header($header);
            exit;
        } else {
            $response = $next($request)->header($header);
        }
        return $response;

    }
}
