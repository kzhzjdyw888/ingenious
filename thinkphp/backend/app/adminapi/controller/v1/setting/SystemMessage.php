<?php

namespace app\adminapi\controller\v1\setting;

use app\adminapi\controller\AuthController;
use app\Request;
use think\App;

class SystemMessage extends AuthController
{
    /**
     * 构造函数
     *
     * @param \app\Request $request
     * @param \think\App   $app
     */
    public function __construct(Request $request, App $app)
    {
        parent::__construct($app);
    }

    /**
     *
     * @param \app\Request $request
     *
     * @return \think\response\Json
     */
    public function message(Request $request): \think\response\Json
    {
        $data = file_get_contents(app()->getRootPath() . 'public/lms_admin/admin/data/message.json');
        $ret  = !empty($data) ? json_decode($data, 1) : [];
        return app('json')->success('Success',$ret);
    }

}
