<?php

namespace app\adminapi\controller\setting;

use app\adminapi\controller\AuthController;
use app\common\Json;
use app\services\system\admin\SystemRoleMenuServices;
use support\Container;
use support\Request;

/**
 * 角色管理
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemRoleMenu extends AuthController
{

    public function __construct()
    {
        parent::__construct();
        $this->services = Container::make(SystemRoleMenuServices::class);
    }

    /**
     * 保存新建的资源
     */
    public function save(Request $request): \support\Response
    {
        $data = $this->request->postMore([
            'role_id',
            'role_name',
            ['checked_menus', []],
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
