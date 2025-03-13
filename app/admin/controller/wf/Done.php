<?php

namespace app\admin\controller\wf;

use app\admin\controller\Base;
use app\admin\controller\wf\trait\TaskHandleTrait;
use app\common\api\WorkflowAPI;
use think\App;

/**
 * 已办任务
 *
 * @author Mr.April
 * @since  1.0
 */
class Done extends Base
{

    use TaskHandleTrait;

    protected array $middleware = ['AdminCheck', 'AdminPermission'];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->service = new WorkflowAPI();
    }

    /**
     * 获取列表
     *
     * @return string
     */
    public function index(): string
    {
        return $this->fetch('wf/task/done/index');
    }

    /**
     * 列表
     *
     * @return \think\Response
     * @throws \Exception
     */
    public function select(): \think\Response
    {

        $format          = input('format', 'normal');
        $methods         = [
            'select'     => 'formatSelect',
            'tree'       => 'formatTree',
            'table_tree' => 'formatTableTree',
            'normal'     => 'formatNormal',
        ];
        $format_function = $methods[$format] ?? 'formatNormal';
        $result          = $this->service->client('task_hist.list', (object)array_merge($this->request->all(), ['actor_id' => getCurrentUser()]));
        return call_user_func([$this, $format_function], $result['items'], $result['total']);
    }

}
