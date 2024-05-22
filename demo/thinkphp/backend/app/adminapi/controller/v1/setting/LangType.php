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
use app\services\system\lang\LangTypeServices;
use phoenix\services\CacheService;
use think\App;

class LangType extends AuthController
{
    /**
     * @param App              $app
     * @param LangTypeServices $services
     */
    public function __construct(App $app, LangTypeServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 获取语言类型列表
     *
     * @param \app\Request $request
     *
     * @return mixed
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function langTypeList(Request $request): mixed
    {
        $where = $this->request->getMore([
            ['language_name', ''],
            ['file_name', ''],
        ]);
        return app('json')->success($this->services->langTypeList($where));
    }

    /**
     * 添加语言类型详情
     *
     * @param \app\Request $request
     *
     * @return mixed
     */
    public function langTypeDetail(Request $request): mixed
    {
        $data = $request->getMore([['id']]);
        return app('json')->success($this->services->get($data['id'])->toArray());
    }

    /**
     * 保存语言类型
     *
     * @return mixed
     */
    public function langTypeSave(): mixed
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
        return app('json')->success(100000);
    }

    /**
     * 修改语言类型状态
     *
     * @param \app\Request $request
     *
     * @return mixed
     */
    public function langTypeStatus(Request $request): mixed
    {
        $data = $request->getParams([['id'], ['status']]);
        $this->services->langTypeStatus($data['id'], $data['status']);
        return app('json')->success(100014);
    }

    /**
     * 删除语言类型
     *
     * @param $id
     *
     * @return mixed
     */
    public function langTypeDel($id): mixed
    {
        $this->services->langTypeDel($id);
        CacheService::delete('lang_type_data');
        return app('json')->success(100002);
    }
}
