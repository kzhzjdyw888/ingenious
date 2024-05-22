<?php

namespace app\adminapi\controller\system;

use app\adminapi\controller\AuthController;
use app\common\Json;
use app\services\system\route\SystemRouteCateServices;
use app\services\system\route\SystemRouteServices;
use support\Container;
use support\Request;

/**
 * Class SystemRouteCate
 *
 * @author  等风来
 * @email   136327134@qq.com
 * @date    2023/4/6
 * @package app\adminapi\controller\v1\setting
 */
class SystemRouteCate extends AuthController
{

    /**
     * SystemRouteCate constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->services = Container::make(SystemRouteCateServices::class);
    }

    /**
     * @author 等风来
     * @email  136327134@qq.com
     * @date   2023/4/6
     */
    public function index(Request $request): \support\Response
    {
        return Json::success($this->services->getAllList());
    }

    /**
     * @param \support\Request $request
     *
     * @return \support\Response
     * @author 等风来
     * @email  136327134@qq.com
     * @date   2023/4/6
     */
    public function create(Request $request): \support\Response
    {
        return Json::success($this->services->getFrom(0, $this->request->get('app_name', 'adminapi')));
    }

    /**
     * @param Request $request
     *
     * @return \support\Response
     * @email  136327134@qq.com
     * @date   2023/4/6
     */
    public function save(Request $request): \support\Response
    {
        $data = $request->postMore([
            ['path', []],
            ['name', ''],
            ['sort', 0],
            ['app_name', ''],
        ]);

        if (!$data['name']) {
            return Json::fail(500037);
        }

        $data['add_time'] = time();
        $data['pid']      = $data['path'][count($data['path']) - 1] ?? 0;
        $this->services->save($data);

        return Json::success(100000);

    }

    /**
     * @param \support\Request $request
     *
     * @return \support\Response
     * @email  136327134@qq.com
     * @date   2023/4/6
     */
    public function edit(Request $request): \support\Response
    {
        $id = $request->input('id');
        return Json::success($this->services->getFrom($id, $this->request->get('app_name', 'adminapi')));
    }

    /**
     * @param Request $request
     *
     * @return \support\Response
     * @email  136327134@qq.com
     * @date   2023/4/6
     */
    public function update(Request $request): \support\Response
    {
        $id   = $request->input('id');
        $data = $request->postMore([
            ['path', []],
            ['name', ''],
            ['sort', 0],
            ['app_name', ''],
        ]);

        if (!$data['name']) {
            return Json::fail(500037);
        }

        $data['pid'] = $data['path'][count($data['path']) - 1] ?? 0;
        $this->services->update($id, $data);

        return Json::success(100001);
    }

    /**
     * @param \support\Request $request
     *
     * @return \support\Response
     * @author 等风来
     * @email  136327134@qq.com
     * @date   2023/4/6
     */
    public function delete(Request $request): \support\Response
    {
        $service = Container::make(SystemRouteServices::class);
        $id      = $request->input('id');
        if (!$id) {
            return Json::fail(500035);
        }
        if ($service->count(['cate_id' => $id])) {
            return Json::fail(500038);
        }
        $this->services->delete($id);

        return Json::success(100002);
    }
}
