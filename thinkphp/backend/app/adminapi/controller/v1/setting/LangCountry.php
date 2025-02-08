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
use app\services\system\lang\LangCountryServices;
use think\App;

/**
 * 地区列表
 *
 * @author Mr.April
 * @since  1.0
 */
class LangCountry extends AuthController
{
    /**
     * @param App                 $app
     * @param LangCountryServices $services
     */
    public function __construct(App $app, LangCountryServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 国家语言列表
     *
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function langCountryList(): mixed
    {
        $where = $this->request->getMore([
            ['keyword', ''],
        ]);
        return app('json')->success($this->services->langCountryList($where));
    }

    /**
     * 获取地区详情
     *
     * @param \app\Request $request
     *
     * @return mixed
     */
    public function langCountryDetail(Request $request): mixed
    {
        return app('json')->success($this->services->get($request['id'])->toArray());
    }

    /**
     * 地区语言修改
     *
     * @param \app\Request $request
     *
     * @return mixed
     */
    public function langCountrySave($id): mixed
    {
        $data = $this->request->postMore([
            ['name', ''],
            ['code', ''],
            ['type_id', 0],
        ]);
        $this->services->langCountrySave($id, $data);
        return app('json')->success(100000);
    }

    /**
     * 地区语言删除
     *
     * @param $id
     *
     * @return mixed
     */
    public function langCountryDel($id): mixed
    {
        $this->services->langCountryDel($id);
        return app('json')->success(100002);
    }
}
