<?php

namespace app\admin\controller\wf;

use app\admin\controller\Base;
use app\admin\controller\wf\trait\TaskHandleTrait;
use app\common\api\WorkflowAPI;
use app\common\util\Json;
use madong\ingenious\enums\ProcessConstEnum;
use madong\ingenious\ex\LFlowException;
use madong\ingenious\libs\utils\ArrayHelper;
use think\App;

/**
 * 待办任务
 *
 * @author Mr.April
 * @since  1.0
 */
class Todo extends Base
{

    use TaskHandleTrait;

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
     */
    public function index(): string
    {
        return $this->fetch('wf/task/todo/index');
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
        $result          = $this->service->client('task.list', (object)array_merge($this->request->all(), ['actor_id' => getCurrentUser()]));
        return call_user_func([$this, $format_function], $result['items'], $result['total']);
    }

    /**
     * @throws \Exception
     */
    public function show(): \think\Response
    {
        $id      = input('get.id');
        $operate = input('get.operate', 'todo');
        $api     = match ($operate) {
            'todo' => 'task.getById',
            'done' => 'task_hist.getById'
        };
        $result  = $this->service->client($api, $id);

        //追加表单json
        $data = [];
        if (!empty($result)) {
            $data    = $result->toArray();
            $content = $data['instance']['define']['content'] ? ArrayHelper::jsonToArray($data['instance']['define']['content']) : [];
            $type    = $content['instance_type'] ?? 2;
            $name    = $content['instance_url'] ?? '';
            if ($type == 1) {
                $result = $this->service->client('form.findByName', $name);
                if (!empty($result)) {
                    $form         = $result->toArray();
                    $data['form'] = $form['latest_history']['content'] ?? [];
                }
            }
        }
        return $data
            ? Json::success('ok', $data)
            : Json::fail('Resource not found');
    }

    /**
     * 处理任务
     *
     * @return \think\Response
     */
    public function execute(): \think\Response
    {

        try {
            $result = $this->service->execute((object)array_merge($this->request->all(), [ProcessConstEnum::OPERATOR_KEY->value => getCurrentUser()]));
            return $result
                ? Json::success('ok')
                : Json::fail('Please try again later');
        } catch (\Throwable $e) {
            return Json::fail($e->getMessage());
        }
    }

    /**
     *  获取可跳转的任务节点名称
     *
     * @return \think\Response
     */
    public function jumpAbleTaskNameList(): \think\Response
    {
        try {
            $processInstanceId = input(ProcessConst::PROCESS_INSTANCE_ID_KEY, '');
            $ingeniousEngine   = $this->services;
            $result            = $ingeniousEngine->processTaskService()->jumpAbleTaskNameList($processInstanceId);
            return Json::success($result);
        } catch (LFlowException $e) {
            return Json::fail($e->getMessage());
        }
    }

    /**
     * 审核处理页面-内置表单
     *
     * @return string
     */
    public function handle_idf(): string
    {
        $id          = input('get.id');
        $operate     = input('get.operate', 'add');
        $instanceUrl = input('get.instance_url');
        $userInfo    = getCurrentUser(true);
        return $this->fetch($instanceUrl, ['id' => $id, 'operate' => $operate, 'nickname' => $userInfo['nickname'] ?? '']);
    }

}
