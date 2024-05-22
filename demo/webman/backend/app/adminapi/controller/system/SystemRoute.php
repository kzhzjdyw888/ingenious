<?php

namespace app\adminapi\controller\system;

use app\adminapi\controller\AuthController;
use app\common\Json;
use app\services\system\route\SystemRouteServices;
use support\Container;
use support\Request;

/**
 * Class SystemRoute
 *
 * @author  等风来
 * @email   136327134@qq.com
 * @date    2023/4/6
 * @package app\adminapi\controller\v1\setting
 */
class SystemRoute extends AuthController
{

    public function __construct()
    {
        parent::__construct();
        $this->services = Container::make(SystemRouteServices::class);
    }

    /**
     * 同步路由权限
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     * @author 等风来
     * @email  136327134@qq.com
     * @date   2023/4/6
     */
    public function syncRoute(Request $request): \support\Response
    {
        $appName = $request->input('appName', 'adminapi');
        $this->services->syncRoute($appName);

        return Json::success(100038);
    }

    /**
     * 列表数据
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     * @author 等风来
     * @email  136327134@qq.com
     * @date   2023/4/7
     */
    public function index(Request $request): \support\Response
    {
        $where = $request->getMore([
            ['name_like', ''],
            ['app_name', 'adminapi'],
        ]);

        return Json::success($this->services->getList($where));
    }

    /**
     * tree数据
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     * @author 等风来
     * @email  136327134@qq.com
     * @date   2023/4/7
     */
    public function tree(Request $request): \support\Response
    {
        [$name, $appName] = $request->getMore([
            ['name_like', ''],
            ['app_name', 'adminapi'],
        ], true);

        return Json::success($this->services->getTreeList($appName, $name));
    }

    /**
     * @param \support\Request $request
     *
     * @return \support\Response
     * @author 等风来
     * @email  136327134@qq.com
     * @date   2023/4/7
     */
    public function save(Request $request): \support\Response
    {
        $id   = $request->input('id', 0);
        $data = $this->request->postMore([
            ['cate_id', 0],
            ['name', ''],
            ['path', ''],
            ['method', ''],
            ['type', 0],
            ['app_name', ''],
            ['request', []],
            ['response', []],
            ['request_example', []],
            ['response_example', []],
            ['describe', ''],
        ]);

//        if (!$data['name']) {
//            return Json::fail(500031);
//        }
//        if (!$data['path']) {
//            return Json::fail(500032);
//        }
//        if (!$data['method']) {
//            return Json::fail(500033);
//        }
//        if (!$data['app_name']) {
//            return Json::fail(500034);
//        }
        if ($id) {
            $this->services->update($id, $data);
        } else {
            $data['add_time'] = date('Y-m-d H:i:s');
            $this->services->save($data);
        }
        $this->services->cacheDriver()->clear();

        return Json::success($id ? 100001 : 100021);
    }

    /**
     * @param \support\Request $request
     *
     * @return \support\Response
     * @author 等风来
     * @email  136327134@qq.com
     * @date   2023/4/7
     */
    public function read(Request $request): \support\Response
    {
        $id = $request->input('id');
        return Json::success($this->services->getInfo((int)$id));
    }

    /**
     * @param $id
     *
     * @return \support\Response
     * @author 等风来
     * @email  136327134@qq.com
     * @date   2023/4/7
     */
    public function delete(Request $request): \support\Response
    {
        $id=$request->input('id');
        if (!$id) {
            return Json::fail(500035);
        }
        $this->services->destroy($id);
        return Json::success(100002);
    }

}
