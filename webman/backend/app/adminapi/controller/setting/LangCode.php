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
use app\services\system\lang\LangCodeServices;
use support\Container;
use support\Request;

class LangCode extends AuthController
{
    public function __construct()
    {
        parent::__construct();
        $this->services = Container::make(LangCodeServices::class);
    }

    /**
     * 获取语言列表
     *
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function langCodeList(Request $request): \support\Response
    {
        $where = $this->request->getMore([
            ['is_admin', 0],
            ['type_id', 0],
            ['code', ''],
            ['remarks', ''],
        ]);
        return Json::success($this->services->langCodeList($where));
    }

    public function langCodeType(Request $request): \support\Response
    {
        return Json::success($this->services->langCodeType());
    }

    /**
     * 获取语言详情
     *
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function langCodeInfo(Request $request): \support\Response
    {
        [$code] = $request->getMore([
            ['code', ''],
        ], true);
        return Json::success($this->services->langCodeInfo($code));
    }

    /**
     * 新增语言
     *
     * @return mixed
     * @throws \Exception
     */
    public function langCodeSave(Request $request): \support\Response
    {
        $data = $request->postMore([
            ['is_admin', 0],
            ['code', ''],
            ['remarks', ''],
            ['edit', 0],
            ['list', []],
        ]);
        $this->services->langCodeSave($data);
        return Json::success('添加成功');
    }

    /**
     * 修改语言
     *
     * @return mixed
     * @throws \Exception
     */
    public function langCodeUpdate(Request $request): \support\Response
    {
        $data = $request->postMore([
            ['is_admin', 0],
            ['code', ''],
            ['remarks', ''],
            ['edit', 1],
            ['list', []],
        ]);
        $this->services->langCodeSave($data);
        return Json::success('修改成功');
    }

    /**
     * 删除语言
     *
     * @param \support\Request $request
     *
     * @return mixed
     */
    public function langCodeDel(Request $request): \support\Response
    {
        $id = $request->input('id');
        $this->services->langCodeDel($id);
        return Json::success(100002);
    }

    /**
     * 机器翻译
     *
     * @param \support\Request $request
     *
     * @return mixed
     */
    public function langCodeTranslate(Request $request): \support\Response
    {
        [$text] = $request->postMore([
            ['text', ''],
        ], true);
        if ($text == '') return Json::fail('参数错误');
        return Json::success($this->services->langCodeTranslate($text));
    }
}
