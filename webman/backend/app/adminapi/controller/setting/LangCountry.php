<?php

namespace app\adminapi\controller\setting;

use app\adminapi\controller\AuthController;
use app\common\Json;
use app\services\system\lang\LangCountryServices;
use support\Container;
use support\Request;

/**
 * 地区列表
 *
 * @author Mr.April
 * @since  1.0
 */
class LangCountry extends AuthController
{
    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->services = Container::make(LangCountryServices::class);
    }

    /**
     * 国家语言列表
     *
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function langCountryList(Request $request): \support\Response
    {
        $where = $request->getMore([
            ['keyword', ''],
        ]);
        return Json::success($this->services->langCountryList($where));
    }

    /**
     * 获取地区详情
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function langCountryDetail(Request $request): \support\Response
    {
        $id = $request->input('id');
        return Json::success($this->services->get($id)->toArray());
    }

    /**
     * 地区语言修改
     *
     * @param \support\Request $request
     *
     * @return mixed
     */
    public function langCountrySave(Request $request): \support\Response
    {
        $id   = $request->input('id');
        $data = $request->postMore([
            ['name', ''],
            ['code', ''],
            ['type_id', 0],
        ]);
        $this->services->langCountrySave($id, $data);
        return Json::success(100000);
    }

    /**
     * 地区语言删除
     *
     * @param \support\Request $request
     *
     * @return mixed
     */
    public function langCountryDel(Request $request): \support\Response
    {
        $id = $request->input('id');
        $this->services->langCountryDel($id);
        return Json::success(100002);
    }
}
