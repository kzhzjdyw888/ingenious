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

namespace app\adminapi;

use phoenix\exceptions\AdminException;
use phoenix\exceptions\ApiException;
use phoenix\exceptions\AuthException;
use think\Container;
use think\db\exception\DbException;
use think\exception\Handle;
use think\exception\ValidateException;
use think\facade\Env;
use think\facade\Log;
use think\Response;
use Throwable;

class AdminApiExceptionHandle extends Handle
{


    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        ValidateException::class,
        AuthException::class,
        AdminException::class,
        ApiException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     * @access public
     * @param Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        if (!$this->isIgnoreReport($exception)) {
            try {
                $data = [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'message' => $this->getMessage($exception),
                    'code' => $this->getCode($exception),
                ];

                //日志内容
                $log = [
                    request()->adminId(),                                                                 //管理员ID
                    request()->ip(),                                                                      //客户ip
                    ceil(msectime() - (request()->time(true) * 1000)),                               //耗时（毫秒）
                    request()->rule()->getMethod(),                                                       //请求类型
                    str_replace("/", "", request()->rootUrl()),                             //应用
                    request()->baseUrl(),                                                                 //路由
                    json_encode(request()->param(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),//请求参数
                    json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),             //报错数据

                ];
                Log::write(implode("|", $log), "error");
            } catch (\Throwable $e) {
                Log::write($e->getMessage(), "error");
            }
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     *
     * @param \think\Request $request
     * @param Throwable      $e
     *
     * @return Response
     */
    public function render(\think\Request $request, Throwable $e): Response
    {
        $massageData = Env::get('app_debug', false) ? [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTrace(),
            'previous' => $e->getPrevious(),
        ] : [];
        $message = $e->getMessage();
        // 添加自定义异常处理机制
        if ($e instanceof AuthException || $e instanceof AdminException || $e instanceof ApiException || $e instanceof ValidateException) {
            return app('json')->make($e->getCode() ?: 400, $message, $massageData);
        } else {
            return app('json')->fail($message, $massageData);
        }
    }

}
