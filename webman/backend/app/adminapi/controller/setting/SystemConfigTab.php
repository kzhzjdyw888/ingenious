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
namespace app\adminapi\controller\setting;

use app\adminapi\controller\AuthController;
use app\common\Json;
use app\services\system\config\SystemConfigServices;
use app\services\system\config\SystemConfigTabServices;
use support\Container;
use support\Request;

/**
 * 系统配置分类
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemConfigTab extends AuthController
{
    /**
     * g构造方法
     * SystemConfigTab constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->services = Container::make(SystemConfigTabServices::class);
    }

    /**
     * 显示资源列表
     *
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(Request $request): \support\Response
    {
        $where = $this->request->getMore([
            ['status', ''],
            ['title', ''],
        ]);
        return Json::success($this->services->getConfgTabList($where));
    }

    /**
     * 显示指定资源
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function read(Request $request): \support\Response
    {
        $id = $request->input('id');
        return Json::success($this->services->get($id)->toArray());
    }

    /**
     * 保存新建的资源
     */
    public function save(Request $request): \support\Response
    {
        $data = $request->postMore([
            'eng_title',
            'status',
            'title',
            'icon',
            ['type', 0],
            ['sort', 0],
            ['pid', 0],
        ]);
        if (is_array($data['pid'])) $data['pid'] = end($data['pid']);
        if (!$data['title']) return Json::fail(400291);
        $this->services->save($data);
        return Json::success(400292);
    }

    /**
     * 保存更新的资源
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function update(Request $request): \support\Response
    {
        $id   = $request->input('id');
        $data = $this->request->postMore([
            'title',
            'status',
            'eng_title',
            'icon',
            ['type', 0],
            ['sort', 0],
            ['pid', 0],
        ]);
        if (is_array($data['pid'])) $data['pid'] = end($data['pid']);
        if (!$data['title']) return Json::fail(400291);
        if (!$data['eng_title']) return Json::fail(400275);
        $this->services->update($id, $data);
        return Json::success(100001);
    }

    /**
     * 删除指定资源
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function delete(Request $request): \support\Response
    {
        $services = Container::make(SystemConfigServices::class);
        $id       = $request->input('id');
        if ($services->count(['tab_id' => $id])) {
            return Json::fail(400293);
        }
        if (!$this->services->delete($id))
            return Json::fail(100008);
        else
            return Json::success(100002);
    }

    /**
     * 修改状态
     *
     * @param \support\Request $request
     *
     * @return mixed
     */
    public function set_status(Request $request): \support\Response
    {
        $id     = $request->input('id');
        $status = $request->input('status');
        if ($status == '' || $id == 0) {
            return Json::fail(100100);
        }
        $this->services->update($id, ['status' => $status]);
        return Json::success(100014);
    }
}
