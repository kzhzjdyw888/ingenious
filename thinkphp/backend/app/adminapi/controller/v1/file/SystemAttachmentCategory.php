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
namespace app\adminapi\controller\v1\file;

use app\adminapi\controller\AuthController;
use app\services\system\attachment\SystemAttachmentCategoryServices;
use think\App;

/**
 * 附件分类管理类
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemAttachmentCategory extends AuthController
{
    /**
     * @var SystemAttachmentCategoryServices
     */
    protected $service;

    /**
     * @param App                              $app
     * @param SystemAttachmentCategoryServices $service
     */
    public function __construct(App $app, SystemAttachmentCategoryServices $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(): \think\Response
    {
        $where = $this->request->getMore([
            ['name', ''],
            ['pid', 0],
            ['all', 0],
        ]);
        if ($where['name'] != '' || $where['all'] == 1) $where['pid'] = '';
        return app('json')->success($this->service->getAll($where));
    }

    /**
     * 保存新增
     *
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function save(): \think\Response
    {
        $data = $this->request->postMore([
            ['pid', '-1'],
            ['name', ''],
            ['sort', 10],
            ['remarks'],
        ]);
        if (!$data['name']) {
            return app('json')->fail(400100);
        }
        $this->service->save($data);
        return app('json')->success(100021);
    }

    /**
     * 获取资源详情
     *
     * @param $id
     *
     * @return \think\Response
     */
    public function read($id): \think\Response
    {
        return app('json')->success($this->service->get($id)->toArray());
    }

    /**
     * 保存更新的资源
     *
     * @param $id
     *
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function update($id): \think\Response
    {
        $data = $this->request->postMore([
            ['pid', '-1'],
            ['name', ''],
            ['sort', 10],
            ['remarks', ''],
        ]);
        if (is_array($data['pid'])) $data['pid'] = end($data['pid']);
        if (!$data['name']) {
            return app('json')->fail(400100);
        }
        $info  = $this->service->get($id);
        $count = $this->service->count(['pid' => $id]);
        if ($count && $info['pid'] != $data['pid']) return app('json')->fail(400105);
        $this->service->update($id, $data);
        return app('json')->success(100001);
    }

    /**
     * 删除指定资源
     *
     * @param string $id
     *
     * @return \think\Response
     */
    public function delete(string $id): \think\Response
    {
        $this->service->del($id);
        return app('json')->success(100002);
    }
}
