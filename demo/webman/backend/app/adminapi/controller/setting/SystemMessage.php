<?php

namespace app\adminapi\controller\setting;

use app\adminapi\controller\AuthController;
use app\common\Json;
use support\Request;

class SystemMessage extends AuthController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function message(Request $request): \support\Response
    {
        $data = file_get_contents(app()->getRootPath() . 'public/lms_admin/admin/data/message.json');
        $ret  = !empty($data) ? json_decode($data, 1) : [];
        return Json::success('Success',$ret);
    }

}
