<?php

namespace app\admin\controller\wf;

use app\admin\controller\Base;
use app\common\api\WorkflowAPI;
use think\App;

class Launch extends Base
{

    protected array $middleware = ['AdminCheck', 'AdminPermission'];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->service = new WorkflowAPI();
    }

    /**
     * @throws \Throwable
     */
    public function index(): string
    {
        return $this->fetch('wf/launch/index');
    }

    /**
     * 发起申请-动态表单
     *
     * @return string
     */
    public function launch_application(): string
    {
        return $this->fetch('wf/launch/launch_application');
    }

    /**
     * 发起申请-内置表单
     *
     * @return string
     */
    public function launch_application_idf(): string
    {
        $id          = input('get.id');
        $operate     = input('operate', 'add');
        $instanceUrl = input('instance_url');
        $userInfo    = getCurrentUser(true);
        return $this->fetch($instanceUrl, ['id' => $id, 'operate' => $operate, 'nickname' => $userInfo['nickname'] ?? '']);
    }
}
