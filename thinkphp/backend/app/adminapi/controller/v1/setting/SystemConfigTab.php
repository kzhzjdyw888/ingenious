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
namespace app\adminapi\controller\v1\setting;

use app\adminapi\controller\AuthController;
use app\services\system\config\SystemConfigServices;
use app\services\system\config\SystemConfigTabServices;
use think\App;

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
     *
     * @param App                     $app
     * @param SystemConfigTabServices $services
     */
    public function __construct(App $app, SystemConfigTabServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 显示资源列表
     *
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(): \think\response\Json
    {
        $where = $this->request->getMore([
            ['status', ''],
            ['title', ''],
        ]);
        return app('json')->success($this->services->getConfgTabList($where));
    }

    /**
     * 显示指定资源
     * @param $id
     *
     * @return \think\response\Json
     */
    public function read($id): \think\response\Json
    {
        return app('json')->success($this->services->get($id)->toArray());
    }

    /**
     * 保存新建的资源
     *
     * @return \think\response\Json
     */
    public function save(): \think\response\Json
    {
        $data = $this->request->postMore([
            'eng_title',
            'status',
            'title',
            'icon',
            ['type', 0],
            ['sort', 0],
            ['pid', 0],
        ]);
        if (is_array($data['pid'])) $data['pid'] = end($data['pid']);
        if (!$data['title']) return app('json')->fail(400291);
        $this->services->save($data);
        return app('json')->success(400292);
    }

    /**
     * 保存更新的资源
     *
     * @param int $id
     *
     * @return \think\response\Json
     */
    public function update($id): \think\response\Json
    {
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
        if (!$data['title']) return app('json')->fail(400291);
        if (!$data['eng_title']) return app('json')->fail(400275);
        $this->services->update($id, $data);
        return app('json')->success(100001);
    }

    /**
     * 删除指定资源
     *
     * @param \app\services\system\config\SystemConfigServices $services
     * @param int                                              $id
     *
     * @return \think\response\Json
     */
    public function delete(SystemConfigServices $services, $id): \think\response\Json
    {
        if ($services->count(['tab_id' => $id])) {
            return app('json')->fail(400293);
        }
        if (!$this->services->delete($id))
            return app('json')->fail(100008);
        else
            return app('json')->success(100002);
    }

    /**
     * 修改状态
     *
     * @param $id
     * @param $status
     *
     * @return mixed
     */
    public function set_status($id, $status): \think\response\Json
    {
        if ($status == '' || $id == 0) {
            return app('json')->fail(100100);
        }
        $this->services->update($id, ['status' => $status]);
        return app('json')->success(100014);
    }
}
