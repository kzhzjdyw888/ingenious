<?php

namespace app\adminapi\controller\setting;

use app\adminapi\controller\AuthController;
use app\common\Json;
use app\services\system\admin\SystemRolePostServices;
use support\Container;
use support\Request;

/**
 * 角色管理
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemRolePost extends AuthController
{

    public function __construct()
    {
        parent::__construct();
        $this->services = Container::make(SystemRolePostServices::class);
    }

    /**
     * 保存新建的资源
     *
     * @throws \Exception
     */
    public function save(Request $request): \support\Response
    {
        $data = $this->request->postMore([
            'role_id',
            'role_name',
            ['checked_post', []],
        ]);
        $message = [
            'role_id' => '100100',
        ];

        $this->validate($data, [
            'role_id' => 'require',
        ], $message, true);

        if (!$this->services->create($data)) return Json::fail(400223);
        $this->services->cacheDriver()->clear();
        return Json::success(400222);
    }
}
