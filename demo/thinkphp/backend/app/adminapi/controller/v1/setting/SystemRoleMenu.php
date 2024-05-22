<?php

namespace app\adminapi\controller\v1\setting;

use app\adminapi\controller\AuthController;
use app\services\system\admin\SystemRoleMenuServices;
use phoenix\services\CacheService;
use think\App;

/**
 * 角色管理
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemRoleMenu extends AuthController
{

    public function __construct(App $app, SystemRoleMenuServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 保存新建的资源
     *
     * @return \think\response\Json
     */
    public function save(): \think\response\Json
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

        if (!$this->services->create($data)) return app('json')->fail(400223);
        $this->services->cacheDriver()->clear();
        return app('json')->success(400222);
    }
}
