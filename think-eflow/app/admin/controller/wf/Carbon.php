<?php

namespace app\admin\controller\wf;

use app\admin\controller\Base;
use app\common\api\WorkflowAPI;
use think\App;

/**
 * 抄送
 *
 * @author Mr.April
 * @since  1.0
 */
class Carbon extends Base
{

    protected array $middleware = ['AdminCheck', 'AdminPermission'];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->service = new WorkflowAPI();
    }

    /**
     * 浏览
     *
     * @return string
     * @throws \Throwable
     */
    public function index(): string
    {
        return $this->fetch('wf/carbon/index');
    }

    /**
     * 查询列表
     *
     * @return \think\Response
     * @throws \Exception
     */
    public function select(): \think\Response
    {
        $format            = input('format', 'normal');
        $methods           = [
            'select'     => 'formatSelect',
            'tree'       => 'formatTree',
            'table_tree' => 'formatTableTree',
            'normal'     => 'formatNormal',
        ];
        $param             = $this->request->all();
        $param['actor_id'] = getCurrentUser();
        $format_function   = $methods[$format] ?? 'formatNormal';
        $result            = $this->service->client('carbon.list', (object)$param);
        return call_user_func([$this, $format_function], $result['items'], $result['total']);
    }

}
