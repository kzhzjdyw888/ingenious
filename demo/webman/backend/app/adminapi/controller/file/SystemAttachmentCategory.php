<?php

namespace app\adminapi\controller\file;

use app\adminapi\controller\AuthController;
use app\common\Json;
use app\services\system\attachment\SystemAttachmentCategoryServices;
use support\Container;
use support\Request;

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
     */
    public function __construct()
    {
        parent::__construct();
        $this->service = Container::make(SystemAttachmentCategoryServices::class);
    }

    /**
     * 显示资源列表
     *
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(Request $request): \support\Response
    {
        $where = $this->request->getMore([
            ['name', ''],
            ['pid', 0],
            ['all', 0],
        ]);
        if ($where['name'] != '' || $where['all'] == 1) $where['pid'] = '';
        return Json::success($this->service->getAll($where));
    }

    /**
     * 保存新增
     *
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function save(Request $request): \support\Response
    {
        $data = $this->request->postMore([
            ['pid', '-1'],
            ['name', ''],
            ['sort', 10],
            ['remarks'],
        ]);
        if (!$data['name']) {
            return Json::fail('请填写分类名称');
        }
        $this->service->save($data);
        return Json::success('添加成功');
    }

    /**
     * 获取资源详情
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function read(Request $request): \support\Response
    {
        $id = $request->input('id');
        return Json::success($this->service->get($id)->toArray());
    }

    /**
     * 保存更新的资源
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function update(Request $request): \support\Response
    {
        $data = $request->postMore([
            ['pid', '-1'],
            ['name', ''],
            ['sort', 10],
            ['remarks', ''],
        ]);
        $id   = $request->input('id');
        if (is_array($data['pid'])) $data['pid'] = end($data['pid']);
        if (!$data['name']) {
            return Json::fail('请填写分类名称');
        }
        $info  = $this->service->get($id);
        $count = $this->service->count(['pid' => $id]);
        if ($count && $info['pid'] != $data['pid']) return Json::fail('该分类有下级分类，无法修改上级');
        $this->service->update($id, $data);
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
        $id=$request->input('id');
        $this->service->del($id);
        return Json::success('删除成功');
    }
}
