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

namespace phoenix\services\workerman;

use app\services\system\admin\SystemAuthServices;
use phoenix\exceptions\AuthException;
use Workerman\Connection\TcpConnection;

class WorkermanHandle
{
    protected $service;

    public function __construct(WorkermanService &$service)
    {
        $this->service = &$service;
    }

    /**
     * @param \Workerman\Connection\TcpConnection  $connection
     * @param array                                $res
     * @param \phoenix\services\workerman\Response $response
     *
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login(TcpConnection &$connection, array $res, Response $response): mixed
    {
        if (!isset($res['data']) || !$token = $res['data']) {
            return $response->close([
                'msg' => '授权失败!',
            ]);
        }
        try {
            /** @var SystemAuthServices $adminAuthService */
            $adminAuthService = app()->make(SystemAuthServices::class);
            $authInfo         = $adminAuthService->parseToken($token);
        } catch (AuthException $e) {
            return $response->close([
                'msg'  => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
        }

        if (!$authInfo || !isset($authInfo['id'])) {
            return $response->close([
                'msg' => '授权失败!',
            ]);
        }
        $connection->adminInfo = $authInfo;
        $connection->adminId   = $authInfo['id'];
        $this->service->setUser($connection);
        return $response->success();
    }
}
