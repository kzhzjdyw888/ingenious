<?php

namespace app\adminapi\controller\setting;

use app\adminapi\controller\AuthController;
use app\common\CacheService;
use app\common\Json;
use app\services\system\lang\LangTypeServices;
use support\Container;
use support\Request;

class LangType extends AuthController
{
    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->services = Container::make(LangTypeServices::class);
    }

    /**
     * 获取语言类型列表
     *
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function langTypeList(Request $request): \support\Response
    {
        $where = $this->request->getMore([
            ['language_name', ''],
            ['file_name', ''],
        ]);
        return Json::success($this->services->langTypeList($where));
    }

    /**
     * 添加语言类型详情
     *
     * @param \support\Request $request
     *
     * @return mixed
     */
    public function langTypeDetail(Request $request): \support\Response
    {
        $data = $request->getMore([['id']]);
        return Json::success($this->services->get($data['id'])->toArray());
    }

    /**
     * 保存语言类型
     *
     * @return mixed
     */
    public function langTypeSave(Request $request): \support\Response
    {
        $data = $this->request->postMore([
            ['id', 0],
            ['language_name', ''],
            ['file_name', ''],
            ['is_default', 0],
            ['status', 0],
        ]);
        $this->services->langTypeSave($data);
        CacheService::delete('lang_type_data');
        return Json::success('保存成功');
    }

    /**
     * 修改语言类型状态
     *
     *
     */
    public function langTypeStatus(Request $request): \support\Response
    {
        $data = $request->getParams([['id'], ['status']]);
        $this->services->langTypeStatus($data['id'], $data['status']);
        return Json::success('修改成功');
    }

    /**
     * 删除语言类型
     *
     * @param \support\Request $request
     *
     * @return mixed
     */
    public function langTypeDel(Request $request): \support\Response
    {
        $id = $request->input('id');
        $this->services->langTypeDel($id);
        CacheService::delete('lang_type_data');
        return Json::success('删除成功');
    }
}
