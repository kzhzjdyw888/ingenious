<?php

namespace app\admin\controller\wf;

use app\admin\controller\Base;
use app\common\api\WorkflowAPI;
use app\common\util\Json;
use think\App;

/**
 * 流程设计
 *
 * @author Mr.April
 * @since  1.0
 */
class Define extends Base
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
     * @throws \Throwable
     */
    public function index(): string
    {
        return $this->fetch('wf/define/index');
    }

    /**
     * 获取列表
     *
     * @return \think\Response
     * @throws \Exception
     */
    public function select(): \think\Response
    {
        $args            = $this->request->getMore([
            ['id', ''],
            ['type_id', ''],
            ['name', ''],
            ['display_name', ''],
            ['instance_url', ''],
            ['state', 1],//默认全部
            ['version', ''],
            ['creator', ''],
            ['is_active', ''],
            ['page', 0],
            ['limit', 0],
        ]);
        $format          = input('format', 'normal');
        $methods         = [
            'select'     => 'formatSelect',
            'tree'       => 'formatTree',
            'table_tree' => 'formatTableTree',
            'normal'     => 'formatNormal',
        ];
        $format_function = $methods[$format] ?? 'formatNormal';
        $result          = $this->service->client('define.list', (object)$args);
        return call_user_func([$this, $format_function], $result['items'], $result['total']);
    }

    /**
     * 获取详情
     *
     * @return \think\Response
     * @throws \Exception
     */
    public function show(): \think\Response
    {
        $id     = input('get.id');
        $result = $this->service->client('define.findById', $id);
        return $result
            ? Json::success('ok', $result->toArray())
            : Json::fail('Resource not found');
    }

    /**
     * 启动流程
     *
     * @return \think\Response
     */
    public function startAndExecute(): \think\Response
    {
        try {
            $result = $this->service->startAndExecute((object)$this->request->all());
            return Json::success('ok', $result);
        } catch (\Throwable $e) {
            return Json::fail($e->getMessage());
        }
    }

    /**
     * 删除流程定义
     *
     * @return \think\Response
     */
    public function delete(): \think\Response
    {
        try {
            $id   = input('id'); // 获取路由地址 id从
            $data = input('data', []);
            $data = !empty($id) && $id !== '0' ? $id : $data;
            if (empty($data)) {
                throw new \Exception('参数错误');
            }
            $result = $this->service->client('define.del', $data);
            return Json::success('ok', $result);
        } catch (\Throwable $e) {
            return Json::fail($e->getMessage());
        }
    }

    /**
     * 详情视图
     *
     * @return string
     */
    public function detail(): string
    {
        return $this->fetch('wf/define/detail');
    }

    /**
     * 流程数据视图
     *
     * @return string
     * @throws \Exception
     */
    public function flowData(): string
    {
        $id   = input('get.id');
        $data = $this->service->client('define.findById', $id);
        return $this->fetch('wf/common/other/json', ['data' => json_encode($data)]);
    }

    /**
     * 流程图
     *
     * @return string
     * @throws \Exception
     */
    public function flowChart(): string
    {
        $id        = input('get.id');
        $result    = $this->service->client('define.findById', $id);
        $graphData = (object)[];
        if (!empty($result)) {
            $content = $result->getData('content');
            if (!empty($content)) {
                $graphData = is_string($content) ? json_decode($content) : $content;
            }
        }
        $data = [
            'viewer'        => true,
            'graphData'     => $graphData,
            'highLight'     => [],
            'commitPath'    => '',
            'defaultConfig' => (object)['grid' => true],
        ];
        return $this->fetch('wf/common/design/index', ['data' => json_encode($data)]);
    }

}
