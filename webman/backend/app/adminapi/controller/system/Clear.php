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
namespace app\adminapi\controller\system;

use app\common\Json;
use app\services\system\log\ClearServices;
use app\adminapi\controller\AuthController;
use support\Container;
use support\Request;

/**
 *
 * 缓存控制类
 * @author Mr.April
 * @since  1.0
 */
class Clear extends AuthController
{
    public function __construct()
    {
        parent::__construct();
        $this->services = Container::make(ClearServices::class);
    }

    /**
     * 刷新数据缓存
     */
    public function refresh_cache(Request $request): \support\Response
    {
        $this->services->refresCache();
        return Json::success(400302);
    }


    /**
     * 删除日志
     */
    public function delete_log(Request $request): \support\Response
    {
        $this->services->deleteLog();
        return Json::success(100002);
    }
}


