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
use app\Request;
use app\services\system\lang\LangCodeServices;
use think\App;

class LangCode extends AuthController
{
    /**
     * @param App              $app
     * @param LangCodeServices $services
     */
    public function __construct(App $app, LangCodeServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 获取语言列表
     *
     * @return mixed
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function langCodeList(): mixed
    {
        $where = $this->request->getMore([
            ['is_admin', 0],
            ['type_id', 0],
            ['code', ''],
            ['remarks', ''],
        ]);
        return app('json')->success($this->services->langCodeList($where));
    }

    public function langCodeType()
    {
        return app('json')->success($this->services->langCodeType());
    }

    /**
     * 获取语言详情
     *
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function langCodeInfo()
    {
        [$code] = $this->request->getMore([
            ['code', ''],
        ], true);
        return app('json')->success($this->services->langCodeInfo($code));
    }

    /**
     * 新增语言
     *
     * @return mixed
     * @throws \Exception
     */
    public function langCodeSave(): mixed
    {
        $data = $this->request->postMore([
            ['is_admin', 0],
            ['code', ''],
            ['remarks', ''],
            ['edit', 0],
            ['list', []],
        ]);
        $this->services->langCodeSave($data);
        return app('json')->success(100000);
    }

    /**
     * 修改语言
     *
     * @return mixed
     * @throws \Exception
     */
    public function langCodeUpdate(): mixed
    {
        $data = $this->request->postMore([
            ['is_admin', 0],
            ['code', ''],
            ['remarks', ''],
            ['edit', 1],
            ['list', []],
        ]);
        $this->services->langCodeSave($data);
        return app('json')->success(100000);
    }

    /**
     * 删除语言
     *
     * @param $id
     *
     * @return mixed
     */
    public function langCodeDel($id): mixed
    {

        $this->services->langCodeDel($id);
        return app('json')->success(100002);
    }

    /**
     * 机器翻译
     *
     * @param \app\Request $request
     *
     * @return mixed
     * @throws \Throwable
     */
    public function langCodeTranslate(Request $request): mixed
    {
        [$text] = $request->postMore([
            ['text', ''],
        ], true);
        if ($text == '') return app('json')->fail(100100);
        return app('json')->success($this->services->langCodeTranslate($text));
    }
}
